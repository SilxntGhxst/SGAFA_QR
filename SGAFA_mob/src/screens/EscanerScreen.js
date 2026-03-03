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
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { CameraView, useCameraPermissions } from "expo-camera";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

export default function EscanerScreen({ navigation }) {
  const [permission, requestPermission] = useCameraPermissions();
  const [modalVisible, setModalVisible] = useState(false);
  const [progreso, setProgreso] = useState(1);
  const totalActivos = 26;

  // Estados para el formulario
  const [estadoSeleccionado, setEstadoSeleccionado] = useState(null);
  const [observacion, setObservacion] = useState("");

  const activoSimulado = {
    codigo: "INV-001",
    nombre: "Proyector",
    ubicacion: "Edificio A",
    responsable: "Andre",
    estadoActual: "Funcional",
  };

  // Manejo de permisos
  if (!permission) return <View style={styles.container} />;
  if (!permission.granted) {
    return (
      <SafeAreaView style={styles.permissionContainer}>
        <Text style={styles.permissionText}>
          S.G.A.F.A QR necesita acceso a la cámara.
        </Text>
        <TouchableOpacity
          style={styles.primaryButton}
          onPress={requestPermission}
        >
          <Text style={styles.primaryButtonText}>Otorgar Permiso</Text>
        </TouchableOpacity>
      </SafeAreaView>
    );
  }

  const handleContinuar = () => {
    setModalVisible(false);
    setEstadoSeleccionado(null);
    setObservacion("");
    if (progreso < totalActivos) setProgreso(progreso + 1);
  };

  const progresoPorcentaje = (progreso / totalActivos) * 100;

  return (
    <View style={styles.container}>
      {/* HEADER UNIFICADO */}
      {/* HEADER UNIFICADO */}
      <View style={styles.headerContainer}>
        <View style={styles.headerLeft}>
          <Image
            source={require("../../assets/logo.png")}
            style={styles.headerLogo}
            resizeMode="contain"
          />
          <View style={styles.headerTextContainer}>
            <Text style={styles.headerTitle}>S.G.A.F.A QR</Text>
            <Text style={styles.headerSubtitle}>Escáner</Text>
          </View>
        </View>
        <TouchableOpacity onPress={() => navigation.navigate("Sincronizacion")}>
          <Feather name="refresh-cw" size={24} color={colors.surface} />
        </TouchableOpacity>
      </View>

      {/* CÁMARA EN EL FONDO */}
      <CameraView style={styles.absoluteCamera} facing="back" />

      {/* OVERLAY DE INTERFAZ (Fondo semi-transparente) */}
      <View style={styles.overlay}>
        {/* Cabecera y Progreso */}
        <View style={styles.headerSection}>
          <View style={styles.navRow}>
            <TouchableOpacity onPress={() => navigation.goBack()}>
              <Feather name="arrow-left" size={28} color={colors.primary} />
            </TouchableOpacity>
            <Text style={styles.screenTitle}>Escanear QR</Text>
            <View style={{ width: 28 }} />
          </View>

          <Text style={styles.progressText}>
            Activos escaneados: ({progreso.toString().padStart(2, "0")}/
            {totalActivos})
          </Text>
          <View style={styles.progressBarBg}>
            <View
              style={[
                styles.progressBarFill,
                { width: `${progresoPorcentaje}%` },
              ]}
            />
          </View>

          <Text style={styles.instructionText}>
            Alinea el código dentro{"\n"}del recuadro
          </Text>
        </View>

        {/* Marco del QR (Centro) */}
        <View style={styles.scannerFrameContainer}>
          <View style={styles.scannerFrame}>
            <View style={[styles.corner, styles.topLeft]} />
            <View style={[styles.corner, styles.topRight]} />
            <View style={[styles.corner, styles.bottomLeft]} />
            <View style={[styles.corner, styles.bottomRight]} />
            {/* Ícono de QR al centro simulando el objetivo */}
            <Feather name="maximize" size={120} color="rgba(255,255,255,0.4)" />
          </View>
        </View>

        {/* Botón Temporal Simulación */}
        <View style={styles.bottomSection}>
          <TouchableOpacity
            style={styles.simulacionButton}
            onPress={() => setModalVisible(true)}
          >
            <Feather name="target" size={20} color={colors.surface} />
            <Text style={styles.simulacionButtonText}>
              Simular Escaneo de Activo
            </Text>
          </TouchableOpacity>
        </View>
      </View>

      {/* --- MODAL: FORMULARIO DE AUDITORÍA --- */}
      <Modal animationType="slide" transparent={true} visible={modalVisible}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <TouchableOpacity onPress={() => setModalVisible(false)}>
                <Feather name="arrow-left" size={28} color={colors.primary} />
              </TouchableOpacity>
              <Text style={styles.modalTitle}>Resultados</Text>
              <View style={{ width: 28 }} />
            </View>

            <ScrollView
              showsVerticalScrollIndicator={false}
              contentContainerStyle={styles.modalScroll}
            >
              <Text style={styles.sectionSubtitle}>Activo Escaneado</Text>

              {/* Tarjeta del Activo */}
              <View style={styles.assetCard}>
                <View style={styles.assetHeader}>
                  <View style={styles.imagePlaceholder}>
                    <Feather
                      name="monitor"
                      size={40}
                      color={colors.textSecondary}
                    />
                  </View>
                  <View style={styles.assetTitles}>
                    <Text style={styles.assetName}>
                      {activoSimulado.nombre}
                    </Text>
                    <Text style={styles.assetCode}>
                      Código:{"\n"}
                      <Text style={{ color: colors.textSecondary }}>
                        {activoSimulado.codigo}
                      </Text>
                    </Text>
                  </View>
                </View>

                <View style={styles.assetDetails}>
                  <View style={styles.detailRow}>
                    <Feather
                      name="map"
                      size={20}
                      color={colors.primary}
                      style={styles.detailIcon}
                    />
                    <Text style={styles.detailText}>
                      Ubicación:{" "}
                      <Text style={styles.detailValue}>
                        {activoSimulado.ubicacion}
                      </Text>
                    </Text>
                  </View>
                  <View style={styles.detailRow}>
                    <Feather
                      name="user"
                      size={20}
                      color={colors.primary}
                      style={styles.detailIcon}
                    />
                    <Text style={styles.detailText}>
                      Responsable:{" "}
                      <Text style={styles.detailValue}>
                        {activoSimulado.responsable}
                      </Text>
                    </Text>
                  </View>
                  <View style={styles.detailRow}>
                    <Feather
                      name="shield"
                      size={20}
                      color={colors.primary}
                      style={styles.detailIcon}
                    />
                    <Text style={styles.detailText}>
                      Estado Actual:{"\n"}
                      <Text style={styles.detailValue}>
                        {activoSimulado.estadoActual}
                      </Text>
                    </Text>
                  </View>
                </View>
              </View>

              {/* Botones de Evaluación */}
              <View style={styles.evaluationContainer}>
                <TouchableOpacity
                  style={[
                    styles.evalButton,
                    estadoSeleccionado === "funcional"
                      ? {
                          backgroundColor: colors.success,
                          borderColor: colors.success,
                        }
                      : { borderColor: colors.success },
                  ]}
                  onPress={() => setEstadoSeleccionado("funcional")}
                >
                  <Feather
                    name="check"
                    size={24}
                    color={
                      estadoSeleccionado === "funcional"
                        ? colors.surface
                        : colors.success
                    }
                  />
                  <Text
                    style={[
                      styles.evalButtonText,
                      estadoSeleccionado === "funcional"
                        ? { color: colors.surface }
                        : { color: colors.success },
                    ]}
                  >
                    Funcional
                  </Text>
                </TouchableOpacity>

                <TouchableOpacity
                  style={[
                    styles.evalButton,
                    estadoSeleccionado === "mantenimiento"
                      ? { backgroundColor: "#eab308", borderColor: "#eab308" }
                      : { borderColor: "#eab308" },
                  ]}
                  onPress={() => setEstadoSeleccionado("mantenimiento")}
                >
                  <Feather
                    name="info"
                    size={24}
                    color={
                      estadoSeleccionado === "mantenimiento"
                        ? colors.surface
                        : "#eab308"
                    }
                  />
                  <Text
                    style={[
                      styles.evalButtonText,
                      estadoSeleccionado === "mantenimiento"
                        ? { color: colors.surface }
                        : { color: "#eab308" },
                    ]}
                  >
                    Manteni-{"\n"}miento
                  </Text>
                </TouchableOpacity>

                <TouchableOpacity
                  style={[
                    styles.evalButton,
                    estadoSeleccionado === "baja"
                      ? {
                          backgroundColor: colors.danger,
                          borderColor: colors.danger,
                        }
                      : { borderColor: colors.danger },
                  ]}
                  onPress={() => setEstadoSeleccionado("baja")}
                >
                  <Feather
                    name="x"
                    size={24}
                    color={
                      estadoSeleccionado === "baja"
                        ? colors.surface
                        : colors.danger
                    }
                  />
                  <Text
                    style={[
                      styles.evalButtonText,
                      estadoSeleccionado === "baja"
                        ? { color: colors.surface }
                        : { color: colors.danger },
                    ]}
                  >
                    Baja
                  </Text>
                </TouchableOpacity>
              </View>

              {/* Observación y Envío */}
              <TextInput
                style={styles.observationInput}
                placeholder="Agregar Observación"
                placeholderTextColor={colors.textSecondary}
                value={observacion}
                onChangeText={setObservacion}
                multiline
              />

              <TouchableOpacity
                style={[
                  styles.primaryButton,
                  !estadoSeleccionado && { backgroundColor: "#94a3b8" },
                ]}
                onPress={handleContinuar}
                disabled={!estadoSeleccionado}
              >
                <Text style={styles.primaryButtonText}>Continuar</Text>
              </TouchableOpacity>
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#a8a29e" },
  // Estilos del Header Unificado
  headerSafeArea: { backgroundColor: colors.primary, zIndex: 10 },
  headerContainer: {
    flexDirection: "row",
    alignItems: "center",
    paddingVertical: 16,
    paddingHorizontal: 24,
    justifyContent: 'space-between',
  },
  headerLeft: { flexDirection: 'row', alignItems: 'center' },
  headerLogo: { width: 44, height: 44, marginRight: 12 },
  headerTextContainer: { justifyContent: "center" },
  headerTitle: {
    color: colors.surface,
    fontSize: 18,
    fontWeight: "800",
    letterSpacing: 0.5,
  },
  headerSubtitle: {
    color: "#94a3b8",
    fontSize: 13,
    fontWeight: "600",
    marginTop: 2,
    textTransform: "uppercase",
  },

  overlay: {
    flex: 1,
    backgroundColor: "rgba(199, 194, 190, 0.36)",
    justifyContent: "space-between",
  },

  absoluteCamera: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
  },

  headerSection: { padding: 24, paddingTop: 32 },
  navRow: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    marginBottom: 24,
  },
  screenTitle: { fontSize: 24, fontWeight: "800", color: colors.primary },
  progressText: {
    fontSize: 14,
    fontWeight: "800",
    color: colors.primary,
    textAlign: "center",
    marginBottom: 8,
  },
  progressBarBg: {
    height: 16,
    backgroundColor: "#e2e8f0",
    borderRadius: 8,
    overflow: "hidden",
    marginBottom: 24,
  },
  progressBarFill: {
    height: "100%",
    backgroundColor: "#22c55e",
    borderRadius: 8,
  },
  instructionText: {
    fontSize: 20,
    fontWeight: "700",
    color: colors.surface,
    textAlign: "center",
    lineHeight: 28,
  },

  // Marco del escáner
  scannerFrameContainer: {
    flex: 1, // Le decimos que tome todo el espacio central disponible
    alignItems: "center",
    justifyContent: "center",
    marginTop: -200, // <--- La incisión: Un margen negativo para forzarlo a subir
  },
  scannerFrame: {
    width: 260,
    height: 260,
    justifyContent: "center",
    alignItems: "center",
    position: "relative",
  },
  corner: {
    position: "absolute",
    width: 50,
    height: 50,
    borderColor: colors.surface,
    borderWidth: 6,
  },
  topLeft: {
    top: 0,
    left: 0,
    borderBottomWidth: 0,
    borderRightWidth: 0,
    borderTopLeftRadius: 24,
  },
  topRight: {
    top: 0,
    right: 0,
    borderBottomWidth: 0,
    borderLeftWidth: 0,
    borderTopRightRadius: 24,
  },
  bottomLeft: {
    bottom: 0,
    left: 0,
    borderTopWidth: 0,
    borderRightWidth: 0,
    borderBottomLeftRadius: 24,
  },
  bottomRight: {
    bottom: 0,
    right: 0,
    borderTopWidth: 0,
    borderLeftWidth: 0,
    borderBottomRightRadius: 24,
  },

  bottomSection: { padding: 24, paddingBottom: 48, alignItems: "center" },
  simulacionButton: {
    backgroundColor: colors.primary,
    flexDirection: "row",
    paddingVertical: 16,
    paddingHorizontal: 24,
    borderRadius: 12,
    alignItems: "center",
  },
  simulacionButtonText: {
    color: colors.surface,
    fontWeight: "800",
    fontSize: 14,
    marginLeft: 8,
  },

  // Permisos
  permissionContainer: {
    flex: 1,
    justifyContent: "center",
    padding: 32,
    backgroundColor: colors.background,
  },
  permissionText: {
    fontSize: 16,
    textAlign: "center",
    marginBottom: 24,
    color: colors.textPrimary,
  },

  // --- MODAL ---
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.4)",
    justifyContent: "flex-end",
  },
  modalContent: {
    backgroundColor: colors.surface,
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    height: "90%",
  },
  modalHeader: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    padding: 24,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  modalTitle: { fontSize: 22, fontWeight: "800", color: colors.primary },
  modalScroll: { padding: 24, paddingBottom: 40 },
  sectionSubtitle: {
    fontSize: 20,
    fontWeight: "800",
    color: colors.primary,
    textAlign: "center",
    marginBottom: 20,
  },

  assetCard: {
    backgroundColor: "#e2e8f0",
    borderRadius: 16,
    padding: 20,
    marginBottom: 24,
  },
  assetHeader: { flexDirection: "row", alignItems: "center", marginBottom: 20 },
  imagePlaceholder: {
    width: 80,
    height: 80,
    backgroundColor: colors.surface,
    borderRadius: 8,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
  },
  assetTitles: { flex: 1 },
  assetName: {
    fontSize: 20,
    fontWeight: "800",
    color: colors.primary,
    marginBottom: 4,
  },
  assetCode: { fontSize: 16, fontWeight: "700", color: colors.textSecondary },

  assetDetails: { gap: 12 },
  detailRow: { flexDirection: "row", alignItems: "center" },
  detailIcon: { marginRight: 12 },
  detailText: {
    fontSize: 16,
    color: colors.textSecondary,
    flex: 1,
    fontWeight: "600",
  },
  detailValue: { fontWeight: "700", color: colors.textPrimary },

  evaluationContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 24,
    gap: 12,
  },
  evalButton: {
    flex: 1,
    backgroundColor: colors.surface,
    borderRadius: 12,
    paddingVertical: 16,
    alignItems: "center",
    borderWidth: 2,
    justifyContent: "center",
  },
  evalButtonText: {
    marginTop: 8,
    fontSize: 14,
    fontWeight: "800",
    textAlign: "center",
  },

  observationInput: {
    backgroundColor: "#e2e8f0",
    borderRadius: 12,
    padding: 16,
    height: 60,
    fontSize: 16,
    color: colors.textPrimary,
    fontWeight: "600",
    marginBottom: 24,
  },

  primaryButton: {
    backgroundColor: "#2563eb",
    paddingVertical: 16,
    borderRadius: 12,
    alignItems: "center",
  },
  primaryButtonText: { color: colors.surface, fontSize: 18, fontWeight: "800" },
});
