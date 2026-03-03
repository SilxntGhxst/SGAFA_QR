import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Image,
  ActivityIndicator,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

export default function SincronizacionScreen({ navigation }) {
  // Estados simulados
  const [isSyncing, setIsSyncing] = useState(false);
  const [registros, setRegistros] = useState([
    {
      id: "1",
      tipo: "Registro de auditoría",
      estado: "Pendiente",
      icon: "camera",
    },
    {
      id: "2",
      tipo: "Reporte de daño",
      estado: "Pendiente",
      icon: "clipboard",
    },
  ]);

  const handleSincronizar = () => {
    setIsSyncing(true);
    // Simulamos el tiempo de subida a la base de datos
    setTimeout(() => {
      setIsSyncing(false);
      setRegistros([]); // Vaciamos la lista al terminar
    }, 2500);
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
            <Text style={styles.headerSubtitle}>Sincronización</Text>
          </View>
        </View>
      </View>

      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContainer}
      >
        {/* NAVEGACIÓN Y TÍTULO */}
        <View style={styles.navHeader}>
          <TouchableOpacity
            onPress={() => navigation.goBack()}
            style={styles.backButton}
          >
            <Feather name="arrow-left" size={28} color={colors.primary} />
          </TouchableOpacity>
          <Text style={styles.title}>Centro de{"\n"}Sincronización</Text>
        </View>

        <Text style={styles.subtitle}>
          Gestiona los registros capturados sin conexión
        </Text>

        {/* ESTADO DE CONEXIÓN */}
        <View style={styles.connectionCard}>
          <View style={styles.connectionIconContainer}>
            <Feather name="wifi-off" size={32} color={colors.warning} />
          </View>
          <View style={styles.connectionTextContainer}>
            <Text style={styles.connectionTitle}>Sin conexión</Text>
            <Text style={styles.connectionTime}>
              Última conexión: Hoy, 10:00 AM
            </Text>
          </View>
        </View>

        {/* LISTA DE PENDIENTES */}
        <View style={styles.listHeader}>
          <Text style={styles.listTitle}>
            {registros.length === 1
              ? "1 registro pendiente de subir"
              : `${registros.length} registros pendientes de subir`}
          </Text>
        </View>

        {registros.length > 0 ? (
          registros.map((item) => (
            <View key={item.id} style={styles.recordCard}>
              <View style={styles.recordIcon}>
                <Feather name={item.icon} size={24} color={colors.primary} />
              </View>
              <View style={styles.recordDetails}>
                <Text style={styles.recordType}>{item.tipo}</Text>
                <Text style={styles.recordStatus}>{item.estado}</Text>
              </View>
              <Feather
                name="upload-cloud"
                size={20}
                color={colors.textSecondary}
              />
            </View>
          ))
        ) : (
          <View style={styles.emptyState}>
            <Feather
              name="check-circle"
              size={48}
              color={colors.success}
              style={{ marginBottom: 16 }}
            />
            <Text style={styles.emptyStateTitle}>Todo está al día</Text>
            <Text style={styles.emptyStateText}>
              No hay registros pendientes por sincronizar.
            </Text>
          </View>
        )}

        {/* BOTÓN DE ACCIÓN */}
        <TouchableOpacity
          style={[
            styles.syncButton,
            (registros.length === 0 || isSyncing) && styles.syncButtonDisabled,
          ]}
          onPress={handleSincronizar}
          disabled={registros.length === 0 || isSyncing}
        >
          {isSyncing ? (
            <ActivityIndicator color={colors.surface} size="small" />
          ) : (
            <Feather name="refresh-cw" size={20} color={colors.surface} />
          )}
          <Text style={styles.syncButtonText}>
            {isSyncing ? "Sincronizando..." : "Sincronizar ahora"}
          </Text>
        </TouchableOpacity>

        <Text style={styles.footerText}>
          Los datos se sincronizarán automáticamente al recuperar la conexión a
          internet.
        </Text>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },

  // Header
  headerContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: colors.primary,
    paddingVertical: 16,
    paddingHorizontal: 24,
    zIndex: 10,
  },
  headerLeft: { flexDirection: "row", alignItems: "center" },
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

  scrollContainer: { padding: 24, paddingBottom: 40 },

  navHeader: { flexDirection: "row", alignItems: "center", marginBottom: 12 },
  backButton: { marginRight: 16, padding: 4 },
  title: {
    fontSize: 26,
    fontWeight: "900",
    color: colors.primary,
    flex: 1,
    lineHeight: 30,
  },
  subtitle: {
    fontSize: 16,
    fontWeight: "600",
    color: colors.textSecondary,
    marginBottom: 32,
    lineHeight: 22,
  },

  // Tarjeta de conexión
  connectionCard: {
    backgroundColor: colors.warningBg,
    borderRadius: 16,
    padding: 20,
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 32,
    borderWidth: 1,
    borderColor: "#fde047",
  },
  connectionIconContainer: {
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: "rgba(255, 255, 255, 0.5)",
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
  },
  connectionTextContainer: { flex: 1 },
  connectionTitle: {
    fontSize: 18,
    fontWeight: "800",
    color: colors.warning,
    marginBottom: 4,
  },
  connectionTime: { fontSize: 14, fontWeight: "600", color: "#a16207" },

  // Lista
  listHeader: { marginBottom: 16 },
  listTitle: { fontSize: 18, fontWeight: "800", color: colors.primary },

  recordCard: {
    backgroundColor: "#e2e8f0",
    borderRadius: 16,
    padding: 16,
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
  },
  recordIcon: {
    width: 48,
    height: 48,
    borderRadius: 12,
    backgroundColor: colors.surface,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
  },
  recordDetails: { flex: 1 },
  recordType: {
    fontSize: 16,
    fontWeight: "800",
    color: colors.primary,
    marginBottom: 4,
  },
  recordStatus: {
    fontSize: 14,
    fontWeight: "600",
    color: colors.textSecondary,
  },

  emptyState: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    padding: 32,
    alignItems: "center",
    marginBottom: 24,
    borderWidth: 1,
    borderColor: "#f1f5f9",
  },
  emptyStateTitle: {
    fontSize: 18,
    fontWeight: "800",
    color: colors.primary,
    marginBottom: 8,
  },
  emptyStateText: {
    fontSize: 14,
    color: colors.textSecondary,
    textAlign: "center",
  },

  // Botón
  syncButton: {
    backgroundColor: colors.accent,
    borderRadius: 16,
    paddingVertical: 18,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    marginTop: 16,
    marginBottom: 24,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 8,
    elevation: 4,
  },
  syncButtonDisabled: { backgroundColor: "#94a3b8", shadowOpacity: 0 },
  syncButtonText: {
    color: colors.surface,
    fontSize: 18,
    fontWeight: "800",
    marginLeft: 12,
  },

  footerText: {
    fontSize: 13,
    color: colors.textSecondary,
    textAlign: "center",
    lineHeight: 20,
    paddingHorizontal: 16,
  },
});
