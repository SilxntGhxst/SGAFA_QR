import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Modal,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

const mockData = {
  usuario: { nombre: "Santiago", rol: "Resguardante / Auditor" },
  metricas: { auditoriasPendientes: 3, activosAsignados: 124 },
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

const mockActividadExtendida = [
  ...mockData.actividadReciente,
  {
    id: "4",
    tipo: "exito",
    titulo: "Silla Ergonómica",
    fecha: "01 Mar, 09:00 AM",
    estado: "Escaneado",
  },
  {
    id: "5",
    tipo: "exito",
    titulo: 'Monitor LG 24"',
    fecha: "01 Mar, 08:45 AM",
    estado: "Escaneado",
  },
  {
    id: "6",
    tipo: "aviso",
    titulo: "Router Cisco",
    fecha: "28 Feb, 14:20 PM",
    estado: "Faltante",
  },
  {
    id: "7",
    tipo: "alerta",
    titulo: "Auditoría Sala Juntas",
    fecha: "27 Feb, 11:00 AM",
    estado: "Pendiente",
  },
  {
    id: "8",
    tipo: "exito",
    titulo: "Impresora HP LaserJet",
    fecha: "26 Feb, 16:15 PM",
    estado: "Escaneado",
  },
  {
    id: "9",
    tipo: "aviso",
    titulo: "Escritorio Dirección",
    fecha: "25 Feb, 10:30 AM",
    estado: "Dañado",
  },
  {
    id: "10",
    tipo: "exito",
    titulo: "Teléfono IP",
    fecha: "25 Feb, 09:00 AM",
    estado: "Escaneado",
  },
  {
    id: "11",
    tipo: "exito",
    titulo: "Pizarra Magnética",
    fecha: "24 Feb, 12:00 PM",
    estado: "Escaneado",
  },
];

export default function DashboardScreen({ navigation }) {
  const [modalVisible, setModalVisible] = useState(false);

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
        return <Feather name="info" size={20} color={colors.info} />;
    }
  };

  // --- NUEVA FUNCIÓN PARA LOS COLORES DE LAS ETIQUETAS ---
  const getBadgeStyle = (tipo) => {
    switch (tipo) {
      case "alerta":
        return { bg: colors.warningBg, text: colors.warning };
      case "exito":
        return { bg: colors.successBg, text: colors.success };
      case "aviso":
        return { bg: colors.dangerBg, text: colors.danger };
      default:
        return { bg: colors.infoBg, text: colors.info };
    }
  };

  // Función auxiliar para renderizar cada fila (usada en Dashboard y Modal)
  const renderItem = (item) => {
    const badgeStyle = getBadgeStyle(item.tipo);
    return (
      <View key={item.id} style={styles.activityItem}>
        <View style={styles.activityIconContainer}>
          {renderActivityIcon(item.tipo)}
        </View>
        <View style={styles.activityDetails}>
          <Text style={styles.activityTitle}>{item.titulo}</Text>
          <Text style={styles.activityDate}>{item.fecha}</Text>
        </View>
        <View style={styles.activityStatus}>
          {/* Aquí aplicamos el recuadro con color dinámico */}
          <View
            style={[styles.statusBadge, { backgroundColor: badgeStyle.bg }]}
          >
            <Text style={[styles.statusText, { color: badgeStyle.text }]}>
              {item.estado}
            </Text>
          </View>
        </View>
      </View>
    );
  };

  return (
    <SafeAreaView style={styles.safeArea} edges={["top"]}>
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContainer}
      >
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Hola, {mockData.usuario.nombre}</Text>
            <Text style={styles.role}>{mockData.usuario.rol}</Text>
          </View>
          <TouchableOpacity
            style={styles.avatarPlaceholder}
            onPress={() => navigation.navigate("Perfil")}
          >
            <Feather name="user" size={24} color={colors.accent} />
          </TouchableOpacity>
        </View>

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

        <Text style={styles.sectionTitle}>Acciones Rápidas</Text>
        <View style={styles.actionsContainer}>
          <TouchableOpacity
            style={styles.primaryAction}
            onPress={() => navigation.navigate("Escaner")}
          >
            <Feather
              name="maximize"
              size={32}
              color={colors.surface}
              style={styles.actionIcon}
            />
            <Text style={styles.primaryActionText}>Escanear Código QR</Text>
          </TouchableOpacity>
          <TouchableOpacity
            style={styles.secondaryAction}
            onPress={() => navigation.navigate("Incidencia")}
          >
            <Feather name="alert-octagon" size={24} color={colors.danger} />
            <Text style={styles.secondaryActionText}>Reportar Incidencia</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.activityHeader}>
          <Text style={styles.sectionTitle}>Actividad Reciente</Text>
          <TouchableOpacity onPress={() => setModalVisible(true)}>
            <Text style={styles.linkText}>Ver todo</Text>
          </TouchableOpacity>
        </View>

        <View style={styles.activityList}>
          {mockData.actividadReciente.map(renderItem)}
        </View>
      </ScrollView>

      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <View style={styles.modalHeader}>
              <Text style={styles.modalTitle}>Historial Completo</Text>
              <TouchableOpacity
                onPress={() => setModalVisible(false)}
                style={styles.closeButton}
              >
                <Feather name="x" size={24} color={colors.textSecondary} />
              </TouchableOpacity>
            </View>
            <ScrollView
              showsVerticalScrollIndicator={false}
              contentContainerStyle={{ paddingBottom: 24 }}
            >
              {mockActividadExtendida.map(renderItem)}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  scrollContainer: { padding: 24, paddingBottom: 40 },
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
  // --- ESTILOS NUEVOS PARA EL BADGE ---
  activityStatus: { paddingLeft: 8 },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
  },
  statusText: { fontSize: 11, fontWeight: "800", textTransform: "uppercase" },

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
    maxHeight: "85%",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: -4 },
    shadowOpacity: 0.1,
    shadowRadius: 12,
    elevation: 10,
  },
  modalHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
    paddingBottom: 16,
    borderBottomWidth: 1,
    borderBottomColor: "#f1f5f9",
  },
  modalTitle: { fontSize: 20, fontWeight: "800", color: colors.primary },
  closeButton: { padding: 4 },
});
