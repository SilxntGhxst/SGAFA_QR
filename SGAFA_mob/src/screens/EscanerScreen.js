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
  const [escaneado, setEscaneado] = useState(false);
  const [activoEscaneado, setActivoEscaneado] = useState(null);
  const [estadoSeleccionado, setEstadoSeleccionado] = useState(null);
  const [observacion, setObservacion] = useState("");

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

  const handleQRScanned = ({ data }) => {
    if (escaneado) return;
    setEscaneado(true);
    setActivoEscaneado({
      codigo: data,
      nombre: "Activo " + data,
      ubicacion: "—",
      responsable: "—",
      estadoActual: "—",
    });
    setModalVisible(true);
  };

  const handleCerrar = () => {
    setModalVisible(false);
    setEstadoSeleccionado(null);
    setObservacion("");
    setActivoEscaneado(null);
    // Pequeño delay para que la cámara no re-escanee de inmediato
    setTimeout(() => setEscaneado(false), 1500);
  };

  return (
    <View style={styles.container}>
      {/* HEADER */}
      <View style={styles.header}>
        <View style={styles.headerLeft}>
          <Image
            source={require("../../assets/logo.png")}
            style={styles.headerLogo}
            resizeMode="contain"
          />
          <View>
            <Text style={styles.headerTitle}>S.G.A.F.A QR</Text>
            <Text style={styles.headerSubtitle}>ESCÁNER</Text>
          </View>
        </View>
        <TouchableOpacity onPress={() => navigation.navigate("Sincronizacion")}>
          <Feather name="refresh-cw" size={22} color={colors.surface} />
        </TouchableOpacity>
      </View>

      {/* CÁMARA */}
      <CameraView
        style={StyleSheet.absoluteFill}
        facing="back"
        barcodeScannerSettings={{ barcodeTypes: ["qr"] }}
        onBarcodeScanned={handleQRScanned}
      />

      {/* OVERLAY */}
      <View style={styles.overlay}>
        {/* Botón volver */}
        <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
          <Feather name="arrow-left" size={22} color={colors.surface} />
        </TouchableOpacity>

        {/* Marco del QR */}
        <View style={styles.frameWrapper}>
          <View style={styles.frame}>
            <View style={[styles.corner, styles.topLeft]} />
            <View style={[styles.corner, styles.topRight]} />
            <View style={[styles.corner, styles.bottomLeft]} />
            <View style={[styles.corner, styles.bottomRight]} />
          </View>
          <Text style={styles.hint}>Apunta al código QR del activo</Text>
        </View>

        <View style={{ height: 80 }} />
      </View>

      {/* MODAL DE RESULTADOS */}
      <Modal animationType="slide" transparent visible={modalVisible}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            {/* Header modal */}
            <View style={styles.modalHeader}>
              <TouchableOpacity onPress={handleCerrar}>
                <Feather name="x" size={26} color={colors.primary} />
              </TouchableOpacity>
              <Text style={styles.modalTitle}>Activo Escaneado</Text>
              <View style={{ width: 26 }} />
            </View>

            <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.modalScroll}>
              {/* Tarjeta del activo */}
              <View style={styles.assetCard}>
                <View style={styles.imagePlaceholder}>
                  <Feather name="box" size={36} color={colors.textSecondary} />
                </View>
                <Text style={styles.assetName}>{activoEscaneado?.nombre ?? "—"}</Text>
                <Text style={styles.assetCode}>{activoEscaneado?.codigo ?? "—"}</Text>

                <View style={styles.divider} />

                <View style={styles.detailRow}>
                  <Feather name="map-pin" size={16} color={colors.primary} />
                  <Text style={styles.detailText}>
                    Ubicación: <Text style={styles.detailValue}>{activoEscaneado?.ubicacion ?? "—"}</Text>
                  </Text>
                </View>
                <View style={styles.detailRow}>
                  <Feather name="user" size={16} color={colors.primary} />
                  <Text style={styles.detailText}>
                    Responsable: <Text style={styles.detailValue}>{activoEscaneado?.responsable ?? "—"}</Text>
                  </Text>
                </View>
                <View style={styles.detailRow}>
                  <Feather name="activity" size={16} color={colors.primary} />
                  <Text style={styles.detailText}>
                    Estado: <Text style={styles.detailValue}>{activoEscaneado?.estadoActual ?? "—"}</Text>
                  </Text>
                </View>
              </View>

              {/* Evaluación */}
              <Text style={styles.sectionLabel}>Actualizar estado</Text>
              <View style={styles.evalRow}>
                {[
                  { key: "funcional",     label: "Funcional",     icon: "check",  color: colors.success },
                  { key: "mantenimiento", label: "Mantenimiento", icon: "tool",   color: "#eab308" },
                  { key: "baja",          label: "Baja",          icon: "x",      color: colors.danger },
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

              {/* Observación */}
              <TextInput
                style={styles.input}
                placeholder="Observación (opcional)"
                placeholderTextColor={colors.textSecondary}
                value={observacion}
                onChangeText={setObservacion}
                multiline
              />

              {/* Guardar */}
              <TouchableOpacity
                style={[styles.primaryButton, !estadoSeleccionado && styles.disabledButton]}
                onPress={handleCerrar}
                disabled={!estadoSeleccionado}
              >
                <Text style={styles.primaryButtonText}>Guardar y continuar</Text>
              </TouchableOpacity>
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: "#000" },

  // Header
  header: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingHorizontal: 20,
    paddingVertical: 14,
    backgroundColor: colors.primary,
    zIndex: 10,
  },
  headerLeft: { flexDirection: "row", alignItems: "center", gap: 12 },
  headerLogo: { width: 38, height: 38 },
  headerTitle: { color: colors.surface, fontSize: 16, fontWeight: "800" },
  headerSubtitle: { color: "#94a3b8", fontSize: 11, fontWeight: "600", letterSpacing: 1 },

  // Overlay
  overlay: {
    ...StyleSheet.absoluteFillObject,
    top: 70, // debajo del header
    justifyContent: "space-between",
    alignItems: "center",
    paddingTop: 16,
  },
  backButton: {
    alignSelf: "flex-start",
    marginLeft: 16,
    backgroundColor: "rgba(0,0,0,0.4)",
    padding: 10,
    borderRadius: 10,
  },

  // Marco escáner
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
    borderColor: "#fff",
    borderWidth: 5,
  },
  topLeft:     { top: 0,    left: 0,  borderBottomWidth: 0, borderRightWidth: 0,  borderTopLeftRadius: 12 },
  topRight:    { top: 0,    right: 0, borderBottomWidth: 0, borderLeftWidth: 0,   borderTopRightRadius: 12 },
  bottomLeft:  { bottom: 0, left: 0,  borderTopWidth: 0,    borderRightWidth: 0,  borderBottomLeftRadius: 12 },
  bottomRight: { bottom: 0, right: 0, borderTopWidth: 0,    borderLeftWidth: 0,   borderBottomRightRadius: 12 },
  hint: {
    color: "#fff",
    fontSize: 15,
    fontWeight: "600",
    textAlign: "center",
    backgroundColor: "rgba(0,0,0,0.4)",
    paddingHorizontal: 16,
    paddingVertical: 8,
    borderRadius: 20,
  },

  // Permiso
  permissionContainer: { flex: 1, justifyContent: "center", alignItems: "center", padding: 32, backgroundColor: colors.background },
  permissionText: { fontSize: 16, textAlign: "center", marginBottom: 24, color: colors.textPrimary },

  // Modal
  modalOverlay: { flex: 1, backgroundColor: "rgba(15,23,42,0.5)", justifyContent: "flex-end" },
  modalContent: { backgroundColor: colors.surface, borderTopLeftRadius: 24, borderTopRightRadius: 24, maxHeight: "88%" },
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

  // Asset card
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
    width: 72,
    height: 72,
    backgroundColor: "#e2e8f0",
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 12,
  },
  assetName: { fontSize: 18, fontWeight: "800", color: colors.primary, textAlign: "center" },
  assetCode: { fontSize: 13, color: colors.textSecondary, fontWeight: "600", marginBottom: 16 },
  divider: { width: "100%", height: 1, backgroundColor: "#e2e8f0", marginBottom: 16 },
  detailRow: { flexDirection: "row", alignItems: "center", gap: 8, marginBottom: 8, alignSelf: "flex-start" },
  detailText: { fontSize: 14, color: colors.textSecondary, fontWeight: "600" },
  detailValue: { color: colors.textPrimary, fontWeight: "700" },

  // Evaluación
  sectionLabel: { fontSize: 14, fontWeight: "700", color: colors.textSecondary, marginBottom: 12, textTransform: "uppercase", letterSpacing: 0.5 },
  evalRow: { flexDirection: "row", gap: 10, marginBottom: 20 },
  evalBtn: {
    flex: 1,
    borderWidth: 2,
    borderRadius: 12,
    paddingVertical: 14,
    alignItems: "center",
    gap: 6,
  },
  evalLabel: { fontSize: 12, fontWeight: "800", textAlign: "center" },

  // Input
  input: {
    backgroundColor: "#f1f5f9",
    borderRadius: 12,
    padding: 14,
    fontSize: 15,
    color: colors.textPrimary,
    fontWeight: "600",
    marginBottom: 20,
    minHeight: 56,
  },

  // Botones
  primaryButton: {
    backgroundColor: colors.primary,
    paddingVertical: 16,
    borderRadius: 12,
    alignItems: "center",
  },
  disabledButton: { backgroundColor: "#94a3b8" },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: "800" },
});
