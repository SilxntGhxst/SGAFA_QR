import React, { useState, useRef } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  TextInput,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
  Image,
  Modal,
  Alert,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { CameraView, useCameraPermissions } from "expo-camera";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

export default function IncidenciaScreen({ navigation }) {
  const [permission, requestPermission] = useCameraPermissions();

  const [descripcion, setDescripcion] = useState("");
  const [ubicacion, setUbicacion] = useState("");
  const [responsable, setResponsable] = useState("Santiago");

  const [isCameraVisible, setIsCameraVisible] = useState(false);
  const [photoUri, setPhotoUri] = useState(null);
  const cameraRef = useRef(null);

  const handleAbrirCamara = async () => {
    if (!permission?.granted) {
      const { granted } = await requestPermission();
      if (!granted) {
        Alert.alert(
          "Permiso denegado",
          "Se necesita acceso a la cámara para tomar la foto del activo.",
        );
        return;
      }
    }
    setIsCameraVisible(true);
  };

  const tomarFoto = async () => {
    if (cameraRef.current) {
      try {
        const photo = await cameraRef.current.takePictureAsync({
          quality: 0.5,
        });
        setPhotoUri(photo.uri);
        setIsCameraVisible(false);
      } catch (error) {
        Alert.alert("Error", "No se pudo capturar la foto.");
      }
    }
  };

  const handleEnviar = () => {
    if (!descripcion.trim() || !ubicacion.trim()) {
      Alert.alert(
        "Campos incompletos",
        "Por favor, ingresa la descripción y la ubicación actual del activo.",
      );
      return;
    }

    Alert.alert(
      "Reporte Enviado",
      "El reporte de activo fantasma/dañado ha sido registrado exitosamente.",
      [{ text: "OK", onPress: () => navigation.goBack() }],
    );
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={["top"]}>
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
            <Text style={styles.headerSubtitle}>Incidencias</Text>
          </View>
        </View>
        <TouchableOpacity onPress={() => navigation.navigate("Sincronizacion")}>
          <Feather name="refresh-cw" size={24} color={colors.surface} />
        </TouchableOpacity>
      </View>

      <KeyboardAvoidingView
        behavior={Platform.OS === "ios" ? "padding" : "height"}
        style={styles.keyboardView}
      >
        <ScrollView
          showsVerticalScrollIndicator={false}
          contentContainerStyle={styles.scrollContainer}
        >
          <View style={styles.navHeader}>
            <TouchableOpacity
              onPress={() => navigation.goBack()}
              style={styles.backButton}
            >
              <Feather name="arrow-left" size={28} color={colors.primary} />
            </TouchableOpacity>
            <Text style={styles.title}>
              Registrar Activo{"\n"}Fantasma/Dañado
            </Text>
          </View>

          <Text style={styles.subtitle}>
            Reporta un activo no identificado o sin etiqueta QR
          </Text>

          {/* --- ZONA DE FOTOGRAFÍA --- */}
          {photoUri ? (
            <View style={styles.photoPreviewContainer}>
              <Image source={{ uri: photoUri }} style={styles.photoPreview} />
              <TouchableOpacity
                style={styles.retakeButton}
                onPress={handleAbrirCamara}
              >
                <Feather name="refresh-cw" size={16} color={colors.surface} />
                <Text style={styles.retakeButtonText}>Cambiar foto</Text>
              </TouchableOpacity>
            </View>
          ) : (
            <TouchableOpacity
              style={styles.cameraButton}
              onPress={handleAbrirCamara}
            >
              <Feather name="camera" size={36} color={colors.surface} />
              <Text style={styles.cameraButtonText}>Tomar foto</Text>
            </TouchableOpacity>
          )}

          {/* --- FORMULARIO --- */}
          <View style={styles.inputGroup}>
            <Text style={styles.label}>Descripción</Text>
            <TextInput
              style={[styles.input, styles.textArea]}
              placeholder="Ejemplo: Escritorio sin etiqueta"
              placeholderTextColor={colors.textSecondary}
              value={descripcion}
              onChangeText={setDescripcion}
              multiline
              textAlignVertical="top"
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Ubicación actual</Text>
            <TextInput
              style={styles.input}
              placeholder="Selecciona la ubicación actual"
              placeholderTextColor={colors.textSecondary}
              value={ubicacion}
              onChangeText={setUbicacion}
            />
          </View>

          <View style={styles.inputGroup}>
            <Text style={styles.label}>Responsable (opcional)</Text>
            <TextInput
              style={styles.input}
              placeholder="Nombre del responsable"
              placeholderTextColor={colors.textSecondary}
              value={responsable}
              onChangeText={setResponsable}
            />
          </View>

          <TouchableOpacity style={styles.submitButton} onPress={handleEnviar}>
            <Text style={styles.submitButtonText}>Enviar reporte</Text>
          </TouchableOpacity>
        </ScrollView>
      </KeyboardAvoidingView>

      {/* --- MODAL DE CÁMARA A PANTALLA COMPLETA --- */}
      <Modal
        visible={isCameraVisible}
        animationType="slide"
        transparent={false}
      >
        <View style={styles.fullCameraContainer}>
          <CameraView
            style={styles.absoluteCamera}
            facing="back"
            ref={cameraRef}
          />

          <SafeAreaView style={styles.cameraOverlayTop} edges={["top"]}>
            <TouchableOpacity
              style={styles.closeCameraButton}
              onPress={() => setIsCameraVisible(false)}
            >
              <Feather name="x" size={28} color={colors.surface} />
            </TouchableOpacity>
          </SafeAreaView>

          <View style={styles.cameraOverlayBottom}>
            <TouchableOpacity
              style={styles.captureButtonOuter}
              onPress={tomarFoto}
            >
              <View style={styles.captureButtonInner} />
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },

  headerContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: colors.primary,
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

  keyboardView: { flex: 1 },
  scrollContainer: { padding: 24, paddingBottom: 40 },

  navHeader: { flexDirection: "row", alignItems: "center", marginBottom: 12 },
  backButton: { marginRight: 16, padding: 4 },
  title: {
    fontSize: 24,
    fontWeight: "900",
    color: colors.primary,
    flex: 1,
    lineHeight: 28,
  },
  subtitle: {
    fontSize: 16,
    fontWeight: "700",
    color: colors.textSecondary,
    marginBottom: 24,
    lineHeight: 22,
  },

  cameraButton: {
    backgroundColor: colors.accent,
    borderRadius: 12,
    paddingVertical: 24,
    alignItems: "center",
    justifyContent: "center",
    flexDirection: "row",
    marginBottom: 28,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 6,
  },
  cameraButtonText: {
    color: colors.surface,
    fontSize: 22,
    fontWeight: "800",
    marginLeft: 12,
  },
  photoPreviewContainer: {
    width: "100%",
    height: 200,
    borderRadius: 12,
    overflow: "hidden",
    marginBottom: 28,
    position: "relative",
  },
  photoPreview: { width: "100%", height: "100%" },
  retakeButton: {
    position: "absolute",
    bottom: 12,
    right: 12,
    backgroundColor: "rgba(15, 23, 42, 0.7)",
    flexDirection: "row",
    alignItems: "center",
    paddingHorizontal: 12,
    paddingVertical: 8,
    borderRadius: 8,
  },
  retakeButtonText: {
    color: colors.surface,
    fontWeight: "700",
    marginLeft: 8,
    fontSize: 14,
  },

  inputGroup: { marginBottom: 20 },
  label: {
    fontSize: 16,
    fontWeight: "800",
    color: colors.primary,
    marginBottom: 8,
  },
  input: {
    backgroundColor: "#e2e8f0",
    borderRadius: 12,
    paddingHorizontal: 16,
    paddingVertical: 14,
    fontSize: 16,
    color: colors.textPrimary,
    fontWeight: "600",
  },
  textArea: { height: 90, paddingTop: 14 },

  submitButton: {
    backgroundColor: colors.accent,
    borderRadius: 12,
    paddingVertical: 16,
    alignItems: "center",
    marginTop: 12,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 4,
  },
  submitButtonText: { color: colors.surface, fontSize: 20, fontWeight: "800" },

  // Estilos del Modal de Cámara
  fullCameraContainer: { flex: 1, backgroundColor: "#000" },
  absoluteCamera: {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
  },

  cameraOverlayTop: { position: "absolute", top: 60, left: 24, zIndex: 10 },
  closeCameraButton: {
    backgroundColor: "rgba(0,0,0,0.5)",
    padding: 12,
    borderRadius: 24,
  },

  cameraOverlayBottom: {
    position: "absolute",
    bottom: 50,
    left: 0,
    right: 0,
    alignItems: "center",
    zIndex: 10,
  },
  captureButtonOuter: {
    width: 80,
    height: 80,
    borderRadius: 40,
    borderWidth: 6,
    borderColor: "rgba(255, 255, 255, 0.4)",
    justifyContent: "center",
    alignItems: "center",
  },
  captureButtonInner: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: colors.surface,
  },
});
