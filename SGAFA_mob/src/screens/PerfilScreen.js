import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Modal,
  TextInput,
  Alert,
  Image,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";
import { useAuth } from "../domain/AuthContext";

export default function PerfilScreen({ navigation }) {
  const { user, logout } = useAuth();

  // --- ESTADOS DE LOS MODALES ---
  const [modalEditarVisible, setModalEditarVisible] = useState(false);
  const [modalPasswordVisible, setModalPasswordVisible] = useState(false);
  const [modalAcercaVisible, setModalAcercaVisible] = useState(false);

  // Datos del usuario desde el contexto (fallback si aún carga)
  const nombreCompleto = user ? `${user.nombre} ${user.apellidos}` : "Usuario";
  const correo = user?.email || "";
  const rol    = user?.rol   || "Resguardante";

  const [editNombre, setEditNombre] = useState(user?.nombre || "");
  const [editEmail,  setEditEmail]  = useState(user?.email  || "");

  const [passActual, setPassActual] = useState("");
  const [passNueva, setPassNueva] = useState("");
  const [passConfirmar, setPassConfirmar] = useState("");

  // --- LÓGICA ---
  const handleCerrarSesion = () => {
    Alert.alert(
      "Cerrar Sesión",
      "¿Estás seguro de que deseas salir de tu cuenta?",
      [
        { text: "Cancelar", style: "cancel" },
        {
          text: "Salir",
          style: "destructive",
          onPress: async () => {
            await logout(); // invalida la sesión en BD y limpia AsyncStorage
            // AppNavigator detecta user=null y redirige a AuthStack automáticamente
          },
        },
      ],
    );
  };

  const handleGuardarPerfil = () => {
    if (!editNombre.trim() || !editEmail.trim()) {
      Alert.alert("Error", "Los campos no pueden estar vacíos.");
      return;
    }
    setModalEditarVisible(false);
    Alert.alert("Éxito", "Perfil actualizado correctamente.");
  };

  const handleGuardarPassword = () => {
    if (!passActual || !passNueva || !passConfirmar) {
      Alert.alert(
        "Error",
        "Por favor completa todos los campos de contraseña.",
      );
      return;
    }
    if (passNueva !== passConfirmar) {
      Alert.alert(
        "Error",
        "La nueva contraseña y su confirmación no coinciden.",
      );
      return;
    }
    setModalPasswordVisible(false);
    setPassActual("");
    setPassNueva("");
    setPassConfirmar("");
    Alert.alert("Éxito", "Tu contraseña ha sido cambiada de forma segura.");
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={["top"]}>
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContainer}
      >
        {/* --- TARJETA DE PERFIL --- */}
        <View style={styles.profileCard}>
          <View style={styles.avatarContainer}>
            <View style={styles.avatarCircle}>
              <Text style={styles.avatarInitial}>
                {(user?.nombre || "U").charAt(0).toUpperCase()}
              </Text>
            </View>
            <View style={styles.verifiedBadge}>
              <Feather name="check" size={14} color={colors.surface} />
            </View>
          </View>

          <Text style={styles.userName}>{nombreCompleto}</Text>
          <Text style={styles.emailText}>{correo}</Text>

          <View style={styles.roleBadge}>
            <Text style={styles.roleText}>Rol: {rol}</Text>
          </View>

          {/* RESUMEN OPERATIVO */}
          <View style={styles.statsContainer}>
            <View style={styles.statBox}>
              <Text style={styles.statNumber}>—</Text>
              <Text style={styles.statLabel}>Activos</Text>
            </View>
            <View style={styles.statDivider} />
            <View style={styles.statBox}>
              <Text style={styles.statNumber}>—</Text>
              <Text style={styles.statLabel}>Auditorías</Text>
            </View>
            <View style={styles.statDivider} />
            <View style={styles.statBox}>
              <Text style={styles.statTextValue}>{user?.creado_en ? user.creado_en.substring(0, 7) : "—"}</Text>
              <Text style={styles.statLabel}>Ingreso</Text>
            </View>
          </View>
        </View>

        {/* --- MENÚ DE OPCIONES --- */}
        <View style={styles.menuContainer}>
          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => setModalEditarVisible(true)}
          >
            <View style={styles.menuItemLeft}>
              <Feather name="edit-2" size={24} color={colors.primary} />
              <Text style={styles.menuItemText}>Editar perfil</Text>
            </View>
            <Feather
              name="chevron-right"
              size={24}
              color={colors.textSecondary}
            />
          </TouchableOpacity>

          <TouchableOpacity
            style={styles.menuItem}
            onPress={() => setModalPasswordVisible(true)}
          >
            <View style={styles.menuItemLeft}>
              <Feather name="lock" size={24} color={colors.primary} />
              <Text style={styles.menuItemText}>Cambiar contraseña</Text>
            </View>
            <Feather
              name="chevron-right"
              size={24}
              color={colors.textSecondary}
            />
          </TouchableOpacity>

          {/* NUEVO BOTÓN: ACERCA DE */}
          <TouchableOpacity
            style={[styles.menuItem, styles.menuItemNoBorder]}
            onPress={() => setModalAcercaVisible(true)}
          >
            <View style={styles.menuItemLeft}>
              <Feather name="info" size={24} color={colors.primary} />
              <Text style={styles.menuItemText}>Acerca de la app</Text>
            </View>
            <Feather
              name="chevron-right"
              size={24}
              color={colors.textSecondary}
            />
          </TouchableOpacity>
        </View>

        {/* --- BOTÓN DE CERRAR SESIÓN --- */}
        <TouchableOpacity
          style={styles.logoutButton}
          onPress={handleCerrarSesion}
        >
          <Feather
            name="log-out"
            size={22}
            color={colors.surface}
            style={styles.logoutIcon}
          />
          <Text style={styles.logoutButtonText}>Cerrar Sesión</Text>
        </TouchableOpacity>
      </ScrollView>

      {/* ========================================================= */}
      {/* MODAL 1: EDITAR PERFIL */}
      {/* ========================================================= */}
      <Modal
        visible={modalEditarVisible}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setModalEditarVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Editar Perfil</Text>
              <TouchableOpacity
                onPress={() => setModalEditarVisible(false)}
                style={styles.closeButton}
              >
                <Feather name="x" size={24} color={colors.textSecondary} />
              </TouchableOpacity>
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Nombre Completo</Text>
              <TextInput
                style={styles.input}
                value={editNombre}
                onChangeText={setEditNombre}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Correo Electrónico</Text>
              <TextInput
                style={styles.input}
                value={editEmail}
                onChangeText={setEditEmail}
                keyboardType="email-address"
                autoCapitalize="none"
              />
            </View>

            <TouchableOpacity
              style={styles.primaryButton}
              onPress={handleGuardarPerfil}
            >
              <Text style={styles.primaryButtonText}>Guardar Cambios</Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* ========================================================= */}
      {/* MODAL 2: CAMBIAR CONTRASEÑA */}
      {/* ========================================================= */}
      <Modal
        visible={modalPasswordVisible}
        animationType="slide"
        transparent={true}
        onRequestClose={() => setModalPasswordVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Cambiar Contraseña</Text>
              <TouchableOpacity
                onPress={() => setModalPasswordVisible(false)}
                style={styles.closeButton}
              >
                <Feather name="x" size={24} color={colors.textSecondary} />
              </TouchableOpacity>
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Contraseña Actual</Text>
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor={colors.textSecondary}
                secureTextEntry
                value={passActual}
                onChangeText={setPassActual}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Nueva Contraseña</Text>
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor={colors.textSecondary}
                secureTextEntry
                value={passNueva}
                onChangeText={setPassNueva}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Confirmar Nueva Contraseña</Text>
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor={colors.textSecondary}
                secureTextEntry
                value={passConfirmar}
                onChangeText={setPassConfirmar}
              />
            </View>

            <TouchableOpacity
              style={styles.primaryButton}
              onPress={handleGuardarPassword}
            >
              <Text style={styles.primaryButtonText}>
                Actualizar Contraseña
              </Text>
            </TouchableOpacity>
          </View>
        </View>
      </Modal>

      {/* ========================================================= */}
      {/* MODAL 3: ACERCA DE LA APP */}
      {/* ========================================================= */}
      <Modal
        visible={modalAcercaVisible}
        animationType="fade"
        transparent={true}
        onRequestClose={() => setModalAcercaVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.aboutContent}>
            <TouchableOpacity
              onPress={() => setModalAcercaVisible(false)}
              style={styles.aboutCloseButton}
            >
              <Feather name="x" size={24} color={colors.textSecondary} />
            </TouchableOpacity>

            <Image
              source={require("../../assets/logo.png")}
              style={styles.aboutLogo}
              resizeMode="contain"
            />
            <Text style={styles.aboutTitle}>S.G.A.F.A QR</Text>
            <Text style={styles.aboutVersion}>Versión 1.0.0 (Build 2026)</Text>

            <Text style={styles.aboutDescription}>
              Sistema Integral de Gestión de Activos Fijos y Auditoría.
              Herramienta diseñada para optimizar el control, seguimiento y
              mantenimiento de inventario mediante tecnología de escaneo QR.
            </Text>

            <View style={styles.aboutDivider} />

            <Text style={styles.aboutCopyright}>© 2026 S.G.A.F.A Systems.</Text>
            <Text style={styles.aboutCopyright}>
              Todos los derechos reservados.
            </Text>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  scrollContainer: { padding: 24, paddingBottom: 40 },

  // Perfil Principal
  profileCard: {
    backgroundColor: colors.surface,
    borderRadius: 24,
    padding: 24,
    alignItems: "center",
    marginBottom: 24,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
    borderWidth: 1,
    borderColor: "#f1f5f9",
  },
  avatarContainer: { position: "relative", marginBottom: 16 },
  avatarCircle: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: "#e0f2fe",
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 4,
    borderColor: colors.surface,
  },
  avatarInitial: { fontSize: 32, fontWeight: "800", color: colors.accent },
  verifiedBadge: {
    position: "absolute",
    bottom: 0,
    right: 0,
    backgroundColor: colors.success,
    width: 24,
    height: 24,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    borderWidth: 2,
    borderColor: colors.surface,
  },
  userName: {
    fontSize: 22,
    fontWeight: "800",
    color: colors.primary,
    marginBottom: 4,
  },
  emailText: {
    fontSize: 14,
    fontWeight: "500",
    color: colors.textSecondary,
    marginBottom: 12,
  },
  roleBadge: {
    backgroundColor: colors.infoBg,
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 12,
    marginBottom: 20,
  },
  roleText: {
    color: colors.info,
    fontWeight: "800",
    fontSize: 12,
    textTransform: "uppercase",
  },

  // Métricas del Usuario
  statsContainer: {
    flexDirection: "row",
    width: "100%",
    borderTopWidth: 1,
    borderTopColor: "#f1f5f9",
    paddingTop: 20,
    justifyContent: "space-between",
    paddingHorizontal: 10,
  },
  statBox: { alignItems: "center", flex: 1 },
  statNumber: { fontSize: 20, fontWeight: "800", color: colors.primary },
  statTextValue: {
    fontSize: 14,
    fontWeight: "800",
    color: colors.primary,
    marginTop: 4,
  },
  statLabel: {
    fontSize: 12,
    fontWeight: "600",
    color: colors.textSecondary,
    marginTop: 4,
  },
  statDivider: { width: 1, height: "100%", backgroundColor: "#f1f5f9" },

  // Menú
  menuContainer: {
    backgroundColor: colors.surface,
    borderRadius: 24,
    paddingHorizontal: 20,
    paddingVertical: 8,
    marginBottom: 32,
    borderWidth: 1,
    borderColor: "#f1f5f9",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
  },
  menuItem: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    paddingVertical: 20,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  menuItemNoBorder: { borderBottomWidth: 0 },
  menuItemLeft: { flexDirection: "row", alignItems: "center" },
  menuItemText: {
    fontSize: 16,
    fontWeight: "700",
    color: colors.primary,
    marginLeft: 16,
  },

  // Botón Cerrar Sesión
  logoutButton: {
    backgroundColor: colors.surface,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    paddingVertical: 18,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: colors.danger,
    borderStyle: "dashed",
  },
  logoutIcon: { marginRight: 12, color: colors.danger },
  logoutButtonText: { color: colors.danger, fontSize: 16, fontWeight: "800" },

  // Modals Formularios (Bottom Sheet)
  modalOverlay: {
    flex: 1,
    backgroundColor: "rgba(15, 23, 42, 0.6)",
    justifyContent: "flex-end",
  },
  modalContent: {
    backgroundColor: colors.surface,
    borderTopLeftRadius: 24,
    borderTopRightRadius: 24,
    padding: 24,
    paddingBottom: 40,
  },
  modalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 24,
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  modalTitle: { fontSize: 20, fontWeight: "800", color: colors.primary },
  closeButton: { padding: 4 },
  inputGroup: { marginBottom: 20 },
  label: {
    fontSize: 13,
    fontWeight: "700",
    color: colors.textSecondary,
    marginBottom: 8,
    textTransform: "uppercase",
  },
  input: {
    backgroundColor: "#f8fafc",
    borderWidth: 1,
    borderColor: "#e2e8f0",
    borderRadius: 12,
    paddingHorizontal: 16,
    height: 52,
    fontSize: 16,
    color: colors.primary,
    fontWeight: "600",
  },
  primaryButton: {
    backgroundColor: colors.accent,
    borderRadius: 12,
    height: 52,
    justifyContent: "center",
    alignItems: "center",
    marginTop: 12,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 4,
  },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: "800" },

  // Modal Acerca de (Center Alert)
  aboutContent: {
    backgroundColor: colors.surface,
    margin: 24,
    borderRadius: 24,
    padding: 32,
    alignItems: "center",
    marginBottom: "auto",
    marginTop: "auto",
    position: "relative",
  },
  aboutCloseButton: { position: "absolute", top: 16, right: 16, padding: 8 },
  aboutLogo: { width: 100, height: 100, marginBottom: 16 },
  aboutTitle: {
    fontSize: 24,
    fontWeight: "900",
    color: colors.primary,
    marginBottom: 4,
  },
  aboutVersion: {
    fontSize: 14,
    fontWeight: "600",
    color: colors.accent,
    marginBottom: 24,
  },
  aboutDescription: {
    fontSize: 14,
    color: colors.textSecondary,
    textAlign: "center",
    lineHeight: 22,
    marginBottom: 24,
  },
  aboutDivider: {
    width: "100%",
    height: 1,
    backgroundColor: "#f1f5f9",
    marginBottom: 16,
  },
  aboutCopyright: {
    fontSize: 12,
    color: "#94a3b8",
    fontWeight: "500",
    textAlign: "center",
  },
});
