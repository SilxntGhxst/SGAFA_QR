import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Modal,
  ActivityIndicator,
  RefreshControl,
} from "react-native";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";
import { useDashboard } from "../domain/useCases/useDashboard";
import MetricCard from "../components/Dashboard/MetricCard";
import ActivityItem from "../components/Dashboard/ActivityItem";

export default function DashboardScreen({ navigation }) {
  const [modalVisible, setModalVisible] = useState(false);
  const { data, isLoading, error, refetch } = useDashboard();

  if (isLoading && !data) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color={colors.accent} />
        <Text style={styles.loadingText}>Cargando panel...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.errorContainer}>
        <Feather name="alert-circle" size={48} color={colors.danger} />
        <Text style={styles.errorText}>{error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={refetch}>
          <Text style={styles.retryText}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.safeArea}>
      <ScrollView
        showsVerticalScrollIndicator={false}
        contentContainerStyle={styles.scrollContainer}
        refreshControl={
          <RefreshControl refreshing={isLoading && !!data} onRefresh={refetch} tintColor={colors.accent} />
        }
      >
        <View style={styles.header}>
          <View>
            <Text style={styles.greeting}>Hola, {data?.usuario?.nombre || "Usuario"}</Text>
            <Text style={styles.role}>{data?.usuario?.rol || ""}</Text>
          </View>
          <TouchableOpacity
            style={styles.avatarPlaceholder}
            onPress={() => navigation.navigate("Perfil")}
          >
            <Feather name="user" size={24} color={colors.accent} />
          </TouchableOpacity>
        </View>

        <View style={styles.metricsContainer}>
          <MetricCard
            value={data?.metricas?.auditoriasPendientes || 0}
            label={"Auditorías\nPendientes"}
          />
          <MetricCard
            value={data?.metricas?.activosAsignados || 0}
            label={"Activos\nAsignados"}
          />
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
          {data?.actividadReciente?.map((item) => (
            <ActivityItem key={item.id} item={item} />
          ))}
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
              {data?.historial?.map((item) => (
                <ActivityItem key={item.id} item={item} />
              ))}
            </ScrollView>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  loadingContainer: { flex: 1, justifyContent: "center", alignItems: "center", backgroundColor: colors.background },
  loadingText: { marginTop: 12, fontSize: 16, color: colors.textSecondary, fontWeight: "600" },
  errorContainer: { flex: 1, justifyContent: "center", alignItems: "center", backgroundColor: colors.background, padding: 24 },
  errorText: { marginTop: 16, fontSize: 16, color: colors.danger, textAlign: "center", fontWeight: "600", marginBottom: 24 },
  retryButton: { backgroundColor: colors.accent, paddingHorizontal: 24, paddingVertical: 12, borderRadius: 12 },
  retryText: { color: colors.surface, fontWeight: "700" },

  scrollContainer: { 
    paddingHorizontal: 24, 
    paddingTop: 40,
    paddingBottom: 40 
  },
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
