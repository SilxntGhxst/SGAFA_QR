import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  Modal,
  TextInput,
  ScrollView,
  Image,
  ActivityIndicator,
  Alert
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { CameraView, useCameraPermissions } from "expo-camera";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";
import { useEscaner } from "../domain/useCases/useEscaner";
import { apiClient } from "../data/api/apiClient";

export default function EscanerScreen({ route, navigation }) {
  const { auditoriaContext } = route.params || {};
  const [permission, requestPermission] = useCameraPermissions();
  
  // Modals state
  const [modalVisible, setModalVisible] = useState(false);
  const [modalFinalVisible, setModalFinalVisible] = useState(false);
  
  const [escaneado, setEscaneado] = useState(false);
  
  // Local asset state
  const [activoEscaneado, setActivoEscaneado] = useState(null);
  const [estadoSeleccionado, setEstadoSeleccionado] = useState(null);
  const [observacion, setObservacion] = useState("");
  
  // Final summary state
  const [resumenFinal, setResumenFinal] = useState("");
  const [isFinishing, setIsFinishing] = useState(false);

  // Progress logic
  const [progresoLocal, setProgresoLocal] = useState(auditoriaContext?.escaneados || 0);
  const metaTotal = auditoriaContext?.total || 0;

  // Domain layer
  const { lookupActivo, submitScannedActivo, isLoading, error, setError } = useEscaner();

  // Permisos
  if (!permission) return <View style={styles.container} />;
  if (!permission.granted) {
    return (
      <SafeAreaView style={styles.permissionContainer}>
        <Feather name="camera-off" size={48} color={colors.textSecondary} style={{ marginBottom: 16 }} />
        <Text style={styles.permissionText}>
          S.G.A.F.A QR necesita acceso a la cámara
        </Text>
        <TouchableOpacity style={styles.primaryButton} onPress={requestPermission}>
          <Text style={styles.primaryButtonText}>Otorgar Permiso</Text>
        </TouchableOpacity>
      </SafeAreaView>
    );
  }

  const handleQRScanned = async ({ data }) => {
    if (escaneado) return;
    setEscaneado(true);

    try {
      const activoData = await lookupActivo(data);
      
      // VALIDACIÓN INTRUSO (Solo para auditorías activas)
      if (auditoriaContext && activoData.ubicacion !== auditoriaContext.ubicacion_nombre) {
         Alert.alert(
            "¡Activo Fuera de Lugar!",
            `Este activo pertenece nativamente a ${activoData.ubicacion}, pero estás en plena auditoría en ${auditoriaContext.ubicacion_nombre}.\n\nSe levantará automáticamente un reporte incidental y no contará hacia el progreso de esta auditoría.`,
            [
               { text: "Ver mis tickets", style: "cancel", onPress: () => {
                   setEscaneado(false);
               } },
               { text: "Confirmar Reporte", style: "destructive", onPress: () => {
                   setEscaneado(false);
                   navigation.navigate("Incidencia", {
                      autoFill: {
                         tipo_incidencia: "fuera_de_lugar",
                         descripcion: `El Activo ${activoData.codigo} (${activoData.nombre}) fue encontrado anómalamente en ${auditoriaContext.ubicacion_nombre} por el auditor ${auditoriaContext.folio}. Sin embargo pertenece en sistema a ${activoData.ubicacion}. Revisión solicitada.`
                      }
                   });
               } }
            ]
         );
         return;
      }
      
      setActivoEscaneado({
        id: activoData.id,
        codigo: activoData.codigo,
        nombre: activoData.nombre,
        ubicacion: activoData.ubicacion,
        responsable: activoData.usuario,
        estadoActual: activoData.estado,
      });

      // Mapear color al picker por consistencia
      const normalizedState = activoData.estado.toLowerCase();
      if (normalizedState.includes("funcional")) setEstadoSeleccionado("funcional");
      else if (normalizedState.includes("mantenimiento")) setEstadoSeleccionado("en mantenimiento");
      else if (normalizedState.includes("baja")) setEstadoSeleccionado("baja");
      else setEstadoSeleccionado("funcional");

      setModalVisible(true);
    } catch (err) {
      Alert.alert("Error", err.message || "Activo no encontrado en base de datos.", [
        { text: "Reintentar", onPress: () => setTimeout(() => setEscaneado(false), 2000) }
      ]);
    }
  };

  const procesarGuardado = async () => {
    if (!activoEscaneado) return;
    
    try {
      const payload = {
        estado: estadoSeleccionado,
        descripcion: observacion || "Confirmado mediante Auditoría S.G.A.F.A.",
      };

      await submitScannedActivo(activoEscaneado.id, payload, auditoriaContext?.id);

      if (auditoriaContext) {
         const newProgress = progresoLocal + 1;
         setProgresoLocal(newProgress);
         
         // ¿Hemos alcanzado la meta?
         if (newProgress >= metaTotal) {
            setModalVisible(false);
            setModalFinalVisible(true); // Lanza la encuesta sumaria
            return;
         }
      }

      Alert.alert("Éxito", "El activo se ha registrado correctamente.", [
        { text: "Continuar escaneo", onPress: () => handleCerrarSilencioso() }
      ]);
      
    } catch (err) {
      Alert.alert("Error de Red", err.message || "No se pudo actualizar el activo.");
    }
  };
  
  const procesarFinalizacion = async () => {
     if (!resumenFinal.trim()) {
        Alert.alert("Advertencia", "Por favor brinda una descripción muy breve o el estado general detectado.");
        return;
     }
     
     setIsFinishing(true);
     try {
       await apiClient(`/auditorias/${auditoriaContext.id}/finalizar`, {
           method: 'PUT',
           body: JSON.stringify({ resumen_final: resumenFinal })
       });
       
       Alert.alert("Misión Cumplida", "¡Auditoría cerrada con éxito! La Web administrativa ha sido notificada al instante.", [
          { text: "Terminar", onPress: () => {
             setModalFinalVisible(false);
             navigation.goBack();
          }}
       ]);
     } catch (e) {
       Alert.alert("Error", "No se completó la auditoría.");
     } finally {
       setIsFinishing(false);
     }
  };

  const handleCerrarSilencioso = () => {
    setModalVisible(false);
    setEstadoSeleccionado(null);
    setObservacion("");
    setActivoEscaneado(null);
    setTimeout(() => setEscaneado(false), 1500);
  };

  // Porcentaje progreso
  const progressRatio = metaTotal > 0 ? (progresoLocal / metaTotal) * 100 : 0;

  return (
    <View style={styles.container}>
      {/* HEADER PRINCIPAL */}
      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <TouchableOpacity onPress={() => navigation.goBack()} style={{ paddingRight: 10 }}>
            <Feather name="arrow-left" size={24} color={colors.surface} />
          </TouchableOpacity>
          <View>
            <Text style={styles.headerTitle}>{ auditoriaContext ? 'MODO AUDITORÍA' : 'ESCÁNER RÁPIDO' }</Text>
            <Text style={styles.headerSubtitle}>{ auditoriaContext ? `Misión: ${auditoriaContext.folio}` : 'Módulo de Inspección Local'}</Text>
          </View>
        </View>
      </View>

      {/* BARRA DE PROGRESO (Opcional) */}
      {auditoriaContext && (
        <View style={styles.progressContainer}>
          <View style={styles.progressHeader}>
             <Text style={styles.progressText}>Activos Validados en Sitio</Text>
             <Text style={styles.progressNumbers}>{progresoLocal} / {metaTotal}</Text>
          </View>
          <View style={styles.progressBarBg}>
             <View style={[styles.progressBarFill, { width: `${Math.min(100, progressRatio)}%` }]} />
             {/* Indicador en vivo de que está lista para finalizarse si ya rebasó */}
             { progresoLocal >= metaTotal && (
                 <View style={[StyleSheet.absoluteFill, {backgroundColor:'rgba(255,255,255,0.4)'}]} />
             )}
          </View>
        </View>
      )}

      {/* CÁMARA */}
      { !modalFinalVisible && (
        <CameraView
            style={auditoriaContext ? styles.cameraShrink : StyleSheet.absoluteFill}
            facing="back"
            barcodeScannerSettings={{ barcodeTypes: ["qr"] }}
            onBarcodeScanned={handleQRScanned}
        >
            <View style={styles.overlay}>
            <View style={styles.frameWrapper}>
                <View style={styles.frame}>
                <View style={[styles.corner, styles.topLeft]} />
                <View style={[styles.corner, styles.topRight]} />
                <View style={[styles.corner, styles.bottomLeft]} />
                <View style={[styles.corner, styles.bottomRight]} />
                </View>
                <Text style={styles.hint}>
                {isLoading ? "Buscando activo..." : "Alinea el código QR del activo"}
                </Text>
            </View>
            
            <View style={styles.floatingActions}>
                <TouchableOpacity 
                style={styles.threatButton} 
                onPress={() => navigation.navigate("Incidencia")}
                >
                <Feather name="alert-octagon" size={20} color={colors.surface} />
                <Text style={styles.threatText}>Forzar Incidencia Rápida</Text>
                </TouchableOpacity>
            </View>
            </View>
        </CameraView>
      )}

      {/* MODAL DE RESULTADOS (NORMAL) */}
      <Modal animationType="slide" transparent visible={modalVisible}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <TouchableOpacity onPress={handleCerrarSilencioso}>
                <Feather name="x" size={26} color={colors.primary} />
              </TouchableOpacity>
              <Text style={styles.modalTitle}>Verificación Aprobada</Text>
              <View style={{ width: 26 }} />
            </View>

            <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.modalScroll}>
              <View style={styles.assetCard}>
                <View style={styles.imagePlaceholder}>
                  <Feather name="check-circle" size={32} color={colors.success} />
                </View>
                <Text style={styles.assetName}>{activoEscaneado?.nombre ?? ""}</Text>
                <Text style={styles.assetCode}>{activoEscaneado?.codigo ?? ""}</Text>
                <View style={styles.divider} />
                <View style={styles.detailRow}>
                  <Text style={styles.detailText}>Ubicación:</Text>
                  <Text style={styles.detailValue}>{activoEscaneado?.ubicacion}</Text>
                </View>
                <View style={styles.detailRow}>
                  <Text style={styles.detailText}>Responsable:</Text>
                  <Text style={styles.detailValue}>{activoEscaneado?.responsable}</Text>
                </View>
              </View>

              <Text style={styles.sectionLabel}>Validar Estado Físico Visual</Text>
              <View style={styles.evalRow}>
                {[
                  { key: "funcional",        label: "Óptimo", icon: "check",  color: colors.success },
                  { key: "en mantenimiento", label: "Desgaste", icon: "tool",   color: "#eab308" },
                  { key: "baja",             label: "Dañado", icon: "x",      color: colors.danger },
                ].map(({ key, label, icon, color }) => {
                  const active = estadoSeleccionado === key;
                  return (
                    <TouchableOpacity
                      key={key}
                      style={[styles.evalBtn, { borderColor: color, backgroundColor: active ? color : colors.surface }]}
                      onPress={() => setEstadoSeleccionado(key)}
                    >
                      <Feather name={icon} size={22} color={active ? colors.surface : color} />
                      <Text style={[styles.evalLabel, { color: active ? colors.surface : color }]}>{label}</Text>
                    </TouchableOpacity>
                  );
                })}
              </View>

              <TextInput
                style={styles.input}
                placeholder="Datos adicionales o problemas detectados..."
                placeholderTextColor={colors.textSecondary}
                value={observacion}
                onChangeText={setObservacion}
                multiline
              />

              <TouchableOpacity
                style={[styles.primaryButton, (!estadoSeleccionado || isLoading) && styles.disabledButton]}
                onPress={procesarGuardado}
                disabled={!estadoSeleccionado || isLoading}
              >
                {isLoading ? (
                  <ActivityIndicator color={colors.surface} />
                ) : (
                   <Text style={styles.primaryButtonText}>{ progresoLocal + 1 >= metaTotal && auditoriaContext ? "Guardar y Finalizar Misión" : "Registrar y Continuar" }</Text>
                )}
              </TouchableOpacity>
            </ScrollView>
          </View>
        </View>
      </Modal>

      {/* MODAL FINAL (CIERRE DE AUDITORÍA) */}
      <Modal animationType="fade" visible={modalFinalVisible} transparent>
         <View style={[styles.modalOverlay, {justifyContent: 'center', padding: 20}]}>
             <View style={{backgroundColor: '#fff', borderRadius: 20, padding: 24, width: '100%', shadowColor: '#000', shadowOpacity: 0.3, shadowRadius: 20}}>
                 <View style={{alignItems: 'center', marginBottom: 20}}>
                     <View style={{width: 60, height: 60, borderRadius: 30, backgroundColor: colors.successBg, justifyContent: 'center', alignItems: 'center', marginBottom: 12}}>
                         <Feather name="award" size={30} color={colors.success} />
                     </View>
                     <Text style={{fontSize: 20, fontWeight: '800', color: colors.primary, textAlign: 'center'}}>Auditoría Completada</Text>
                     <Text style={{fontSize: 14, color: colors.textSecondary, textAlign: 'center', marginTop: 8}}>
                         Se han contabilizado los {metaTotal} activos físicos exitosamente en {auditoriaContext?.ubicacion_nombre}.
                     </Text>
                 </View>
                 
                 <Text style={{fontSize: 13, fontWeight: '700', color: colors.primary, marginBottom: 8}}>Sintesis del Recorrido</Text>
                 <TextInput 
                     style={[styles.input, {minHeight: 100, textAlignVertical: 'top'}]}
                     placeholder="Ej: Todos los activos estaban en su lugar excepto las PCs de la fila trasera."
                     value={resumenFinal}
                     onChangeText={setResumenFinal}
                     multiline
                 />

                 <TouchableOpacity 
                     style={styles.primaryButton}
                     onPress={procesarFinalizacion}
                     disabled={isFinishing}
                 >
                     { isFinishing ? <ActivityIndicator color="#fff" /> : <Text style={styles.primaryButtonText}>Enviar Reporte Final</Text> }
                 </TouchableOpacity>
             </View>
         </View>
      </Modal>

    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#000" },

  header: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 14,
    backgroundColor: colors.primary,
    zIndex: 10,
    paddingTop: 45 
  },
  headerLeft: { flexDirection: "row", alignItems: "center", gap: 12 },
  headerTitle: { color: colors.surface, fontSize: 16, fontWeight: "800" },
  headerSubtitle: { color: "#94a3b8", fontSize: 11, fontWeight: "600", letterSpacing: 1 },

  progressContainer: {
     backgroundColor: colors.surface,
     paddingHorizontal: 20,
     paddingVertical: 14,
     borderBottomWidth: 1,
     borderBottomColor: "#e2e8f0"
  },
  progressHeader: { flexDirection: "row", justifyContent: "space-between", marginBottom: 8 },
  progressText: { fontSize: 13, fontWeight: "700", color: colors.textSecondary },
  progressNumbers: { fontSize: 13, fontWeight: "800", color: colors.accent, fontFamily: "monospace" },
  progressBarBg: { height: 8, backgroundColor: "#f1f5f9", borderRadius: 4, overflow: "hidden" },
  progressBarFill: { height: "100%", backgroundColor: colors.accent },

  cameraShrink: { flex: 1 },

  overlay: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
  },

  frameWrapper: { alignItems: "center", gap: 20 },
  frame: {
    width: 240,
    height: 240,
    position: "relative",
    justifyContent: "center",
    alignItems: "center",
  },
  corner: {
    position: "absolute",
    width: 44,
    height: 44,
    borderColor: "#10b981", 
    borderWidth: 5,
  },
  topLeft:     { top: 0,    left: 0,  borderBottomWidth: 0, borderRightWidth: 0,  borderTopLeftRadius: 12 },
  topRight:    { top: 0,    right: 0, borderBottomWidth: 0, borderLeftWidth: 0,   borderTopRightRadius: 12 },
  bottomLeft:  { bottom: 0, left: 0,  borderTopWidth: 0,    borderRightWidth: 0,  borderBottomLeftRadius: 12 },
  bottomRight: { bottom: 0, right: 0, borderTopWidth: 0,    borderLeftWidth: 0,   borderBottomRightRadius: 12 },
  hint: {
    color: "#fff",
    fontSize: 15,
    fontWeight: "700",
    textAlign: "center",
    backgroundColor: "rgba(0,0,0,0.6)",
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 20,
  },

  floatingActions: {
      position: "absolute",
      bottom: 40,
      width: "100%",
      alignItems: "center",
  },
  threatButton: {
      flexDirection: "row",
      alignItems: "center",
      gap: 8,
      backgroundColor: "rgba(220, 38, 38, 0.85)", 
      paddingHorizontal: 24,
      paddingVertical: 14,
      borderRadius: 100,
      borderWidth: 1,
      borderColor: "rgba(239, 68, 68, 0.5)",
  },
  threatText: { color: colors.surface, fontWeight: "800", fontSize: 14 },

  permissionContainer: { flex: 1, justifyContent: "center", alignItems: "center", padding: 32, backgroundColor: colors.background },
  permissionText: { fontSize: 16, textAlign: "center", marginBottom: 24, color: colors.textPrimary },

  modalOverlay: { flex: 1, backgroundColor: "rgba(15,23,42,0.8)", justifyContent: "flex-end" },
  modalContent: { backgroundColor: colors.surface, borderTopLeftRadius: 24, borderTopRightRadius: 24, maxHeight: "90%" },
  modalHeader: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  modalTitle: { fontSize: 18, fontWeight: "800", color: colors.primary },
  modalScroll: { padding: 20, paddingBottom: 40 },

  assetCard: {
    backgroundColor: "#f8fafc",
    borderRadius: 16,
    padding: 20,
    alignItems: "center",
    marginBottom: 24,
    borderWidth: 1,
    borderColor: "#e2e8f0",
  },
  imagePlaceholder: {
    width: 60,
    height: 60,
    backgroundColor: colors.surface,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
    shadowColor: colors.primary,
    shadowOpacity: 0.1,
    shadowRadius: 5,
    elevation: 2
  },
  assetName: { fontSize: 18, fontWeight: "800", color: colors.primary, textAlign: "center" },
  assetCode: { fontSize: 13, color: colors.textSecondary, fontWeight: "600", marginBottom: 16 },
  divider: { width: "100%", height: 1, backgroundColor: "#e2e8f0", marginBottom: 16 },
  detailRow: { flexDirection: "row", alignItems: "center", justifyContent: "space-between", width: "100%", marginBottom: 8 },
  detailText: { fontSize: 13, color: colors.textSecondary, fontWeight: "600" },
  detailValue: { color: colors.primary, fontWeight: "800", fontSize: 13 },

  sectionLabel: { fontSize: 13, fontWeight: "800", color: colors.textSecondary, marginBottom: 12, textTransform: "uppercase", letterSpacing: 0.5 },
  evalRow: { flexDirection: "row", gap: 8, marginBottom: 20 },
  evalBtn: {
    flex: 1,
    borderWidth: 2,
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: "center",
    gap: 6,
  },
  evalLabel: { fontSize: 11, fontWeight: "800", textAlign: "center" },

  input: {
    backgroundColor: "#f1f5f9",
    borderRadius: 12,
    padding: 14,
    fontSize: 14,
    color: colors.textPrimary,
    fontWeight: "600",
    marginBottom: 20,
    minHeight: 56,
  },

  primaryButton: {
    backgroundColor: colors.accent,
    paddingVertical: 16,
    borderRadius: 12,
    alignItems: "center",
  },
  disabledButton: { backgroundColor: "#94a3b8" },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: "800" },
});
