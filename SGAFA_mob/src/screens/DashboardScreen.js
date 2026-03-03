import React from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

// --- API FALSA (Mock Data) ---
const mockData = {
  usuario: {
    nombre: "Santiago",
    rol: "Resguardante / Auditor",
  },
  metricas: {
    auditoriasPendientes: 3,
    activosAsignados: 124,
  },
  actividadReciente: [
    {
      id: "1",
      tipo: "alerta",
      titulo: "Auditoría Laboratorio A",
      fecha: "Hoy, 10:00 AM",
      estado: "Pendiente",
    },
    {
      id: "2",
      tipo: "exito",
      titulo: "Laptop Dell XPS 15",
      fecha: "Ayer, 16:30 PM",
      estado: "Escaneado",
    },
    {
      id: "3",
      tipo: "aviso",
      titulo: "Proyector Epson",
      fecha: "Ayer, 11:15 AM",
      estado: "Reporte Daño",
    },
  ],
};

export default function DashboardScreen({ navigation }) {
  // Función auxiliar para renderizar el ícono correcto según el tipo de actividad
  const renderActivityIcon = (tipo) => {
    switch (tipo) {
      case "alerta":
        return <Feather name="clock" size={20} color={colors.warning} />;
      case "exito":
        return <Feather name="check-circle" size={20} color={colors.success} />;
      case "aviso":
        return (
          <Feather name="alert-triangle" size={20} color={colors.danger} />
        );
      default:
        return <Feather name="info" size={20} color={colors.accent} />;
    }
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={["top"]}>
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContainer}
      >
        {/* --- CABECERA --- */}
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Hola, {mockData.usuario.nombre}</Text>
            <Text style={styles.role}>{mockData.usuario.rol}</Text>
          </View>
          <View style={styles.avatarPlaceholder}>
            <Feather name="user" size={24} color={colors.accent} />
          </View>
        </View>

        {/* --- MÉTRICAS (Glance Value) --- */}
        <View style={styles.metricsContainer}>
          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>
              {mockData.metricas.auditoriasPendientes}
            </Text>
            <Text style={styles.metricLabel}>Auditorías{"\n"}Pendientes</Text>
          </View>
          <View style={styles.metricCard}>
            <Text style={styles.metricValue}>
              {mockData.metricas.activosAsignados}
            </Text>
            <Text style={styles.metricLabel}>Activos{"\n"}Asignados</Text>
          </View>
        </View>

        {/* --- ACCIONES RÁPIDAS --- */}
        <Text style={styles.sectionTitle}>Acciones Rápidas</Text>
        <View style={styles.actionsContainer}>
          <TouchableOpacity style={styles.primaryAction}>
            <Feather
              name="maximize"
              size={32}
              color={colors.surface}
              style={styles.actionIcon}
            />
            <Text style={styles.primaryActionText}>Escanear Código QR</Text>
          </TouchableOpacity>

          <TouchableOpacity style={styles.secondaryAction}>
            {/* Le quitamos el style al ícono y lo dejamos limpio */}
            <Feather name="alert-octagon" size={24} color={colors.danger} />
            <Text style={styles.secondaryActionText}>Reportar Incidencia</Text>
          </TouchableOpacity>
        </View>

        {/* --- ACTIVIDAD RECIENTE --- */}
        <View style={styles.activityHeader}>
          <Text style={styles.sectionTitle}>Actividad Reciente</Text>
          <TouchableOpacity>
            <Text style={styles.linkText}>Ver todo</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.activityList}>
          {mockData.actividadReciente.map((item) => (
            <View key={item.id} style={styles.activityItem}>
              <View style={styles.activityIconContainer}>
                {renderActivityIcon(item.tipo)}
              </View>
              <View style={styles.activityDetails}>
                <Text style={styles.activityTitle}>{item.titulo}</Text>
                <Text style={styles.activityDate}>{item.fecha}</Text>
              </View>
              <View style={styles.activityStatus}>
                <Text
                  style={[
                    styles.statusText,
                    item.tipo === "alerta" && { color: colors.warning },
                    item.tipo === "exito" && { color: colors.success },
                    item.tipo === "aviso" && { color: colors.danger },
                  ]}
                >
                  {item.estado}
                </Text>
              </View>
            </View>
          ))}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  scrollContainer: { padding: 24, paddingBottom: 40 },

  // Header
  header: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 32,
  },
  greeting: {
    fontSize: 28,
    fontWeight: "800",
    color: colors.primary,
    letterSpacing: -0.5,
  },
  role: {
    fontSize: 14,
    color: colors.textSecondary,
    marginTop: 4,
    fontWeight: "500",
  },
  avatarPlaceholder: {
    width: 48,
    height: 48,
    borderRadius: 24,
    backgroundColor: "#dbeafe",
    justifyContent: "center",
    alignItems: "center",
  },

  // Metrics
  metricsContainer: {
    flexDirection: "row",
    justifyContent: "space-between",
    marginBottom: 32,
  },
  metricCard: {
    flex: 1,
    backgroundColor: colors.surface,
    padding: 20,
    borderRadius: 16,
    marginHorizontal: 4,
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
    borderWidth: 1,
    borderColor: "#f1f5f9",
  },
  metricValue: {
    fontSize: 32,
    fontWeight: "800",
    color: colors.accent,
    marginBottom: 8,
  },
  metricLabel: {
    fontSize: 13,
    color: colors.textSecondary,
    fontWeight: "600",
    lineHeight: 18,
  },

  // Actions
  sectionTitle: {
    fontSize: 18,
    fontWeight: "700",
    color: colors.primary,
    marginBottom: 16,
  },
  actionsContainer: { marginBottom: 32 },
  primaryAction: {
    backgroundColor: colors.accent,
    borderRadius: 16,
    padding: 24,
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 12,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 4,
  },
  primaryActionText: {
    color: colors.surface,
    fontSize: 18,
    fontWeight: "700",
    marginTop: 12,
  },
  actionIcon: { marginBottom: 8 },
  secondaryAction: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    padding: 20,
    alignItems: "center",
    justifyContent: "center",
    borderWidth: 1,
    borderColor: "#fee2e2",
    flexDirection: "row",
  },
  secondaryActionText: {
    color: colors.danger,
    fontSize: 16,
    fontWeight: "700",
    marginLeft: 12,
  },

  // Activity
  activityHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
  },
  linkText: { color: colors.accent, fontSize: 14, fontWeight: "600" },
  activityList: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    padding: 8,
    borderWidth: 1,
    borderColor: "#f1f5f9",
  },
  activityItem: {
    flexDirection: "row",
    alignItems: "center",
    padding: 12,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  activityIconContainer: {
    width: 40,
    height: 40,
    borderRadius: 12,
    backgroundColor: "#f8fafc",
    justifyContent: "center",
    alignItems: "center",
    marginRight: 12,
  },
  activityDetails: { flex: 1 },
  activityTitle: {
    fontSize: 15,
    fontWeight: "600",
    color: colors.textPrimary,
    marginBottom: 4,
  },
  activityDate: { fontSize: 12, color: colors.textSecondary },
  activityStatus: { paddingLeft: 12 },
  statusText: { fontSize: 12, fontWeight: "700" },
});
