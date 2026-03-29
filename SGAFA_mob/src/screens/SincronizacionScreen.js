import React, { useState, useEffect } from "react";
import {
  View,
  Text,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Image,
  ActivityIndicator,
  Alert,
  RefreshControl
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { Feather, Ionicons } from "@expo/vector-icons";
import { colors } from "../theme/colors";
import { SyncManager } from "../data/sync/syncManager";
import { apiClient } from "../data/api/apiClient";

export default function SincronizacionScreen({ navigation }) {
  const [queue, setQueue] = useState([]);
  const [isSyncing, setIsSyncing] = useState(false);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadQueue();
  }, []);

  const loadQueue = async () => {
    const data = await SyncManager.getQueue();
    setQueue(data);
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadQueue();
    setRefreshing(false);
  };

  const handleSync = async () => {
    if (queue.length === 0) {
      Alert.alert('Info', 'No hay datos pendientes de sincronizar.');
      return;
    }

    setIsSyncing(true);
    let successCount = 0;
    let failCount = 0;

    for (const item of queue) {
      try {
        const response = await apiClient(item.endpoint, {
          method: item.method,
          body: JSON.stringify(item.data)
        });

        if (response) {
          await SyncManager.removeFromQueue(item.id);
          successCount++;
        } else {
          failCount++;
        }
      } catch (e) {
        console.error('Error sincronizando item:', item.id, e);
        failCount++;
      }
    }

    setIsSyncing(false);
    await loadQueue();

    if (failCount === 0) {
      Alert.alert('Éxito', `Se sincronizaron ${successCount} elementos correctamente.`);
    } else {
      Alert.alert('Sincronización parcial', `Éxito: ${successCount}, Fallos: ${failCount}. Revisa tu conexión.`);
    }
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
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
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

        {/* ESTADO DE LA COLA */}
        <View style={styles.connectionCard}>
          <View style={styles.connectionIconContainer}>
            <Feather name="cloud-upload" size={32} color={colors.primary} />
          </View>
          <View style={styles.connectionTextContainer}>
            <Text style={styles.connectionTitle}>{queue.length} pendientes</Text>
            <Text style={styles.connectionTime}>
              Elementos esperando subir a la nube
            </Text>
          </View>
        </View>

        {/* LISTA DE PENDIENTES */}
        <View style={styles.listHeader}>
          <Text style={styles.listTitle}>Cola de Procesamiento</Text>
        </View>

        {queue.length > 0 ? (
          queue.map((item) => (
            <View key={item.id} style={styles.recordCard}>
              <View style={styles.recordIcon}>
                <Feather name={item.method === 'POST' ? 'plus-circle' : 'edit'} size={24} color={colors.primary} />
              </View>
              <View style={styles.recordDetails}>
                <Text style={styles.recordType}>{item.endpoint}</Text>
                <Text style={styles.recordStatus}>Pendiente - {new Date(item.timestamp).toLocaleTimeString()}</Text>
              </View>
              <Feather name="clock" size={20} color={colors.textSecondary} />
            </View>
          ))
        ) : (
          <View style={styles.emptyState}>
            <Feather name="check-circle" size={48} color={colors.success} style={{ marginBottom: 16 }} />
            <Text style={styles.emptyStateTitle}>Todo está al día</Text>
            <Text style={styles.emptyStateText}>No hay registros pendientes por sincronizar.</Text>
          </View>
        )}
      </ScrollView>

      {/* BOTÓN DE ACCIÓN */}
      <View style={{ padding: 24, backgroundColor: '#fff' }}>
        <TouchableOpacity
          style={[
            styles.syncButton,
            (queue.length === 0 || isSyncing) && styles.syncButtonDisabled,
          ]}
          onPress={handleSync}
          disabled={queue.length === 0 || isSyncing}
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
      </View>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  headerContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-between",
    backgroundColor: colors.primary,
    paddingVertical: 16,
    paddingHorizontal: 24,
  },
  headerLeft: { flexDirection: "row", alignItems: "center" },
  headerLogo: { width: 44, height: 44, marginRight: 12 },
  headerTextContainer: { justifyContent: "center" },
  headerTitle: { color: colors.surface, fontSize: 18, fontWeight: "800", letterSpacing: 0.5 },
  headerSubtitle: { color: "#94a3b8", fontSize: 13, fontWeight: "600", marginTop: 2, textTransform: "uppercase" },
  scrollContainer: { padding: 24, paddingBottom: 40 },
  navHeader: { flexDirection: "row", alignItems: "center", marginBottom: 12 },
  backButton: { marginRight: 16, padding: 4 },
  title: { fontSize: 26, fontWeight: "900", color: colors.primary, flex: 1, lineHeight: 30 },
  subtitle: { fontSize: 16, fontWeight: "600", color: colors.textSecondary, marginBottom: 32, lineHeight: 22 },
  connectionCard: {
    backgroundColor: "#eff6ff",
    borderRadius: 16,
    padding: 20,
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 32,
    borderWidth: 1,
    borderColor: "#dbeafe",
  },
  connectionIconContainer: {
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: colors.surface,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
    elevation: 2
  },
  connectionTextContainer: { flex: 1 },
  connectionTitle: { fontSize: 18, fontWeight: "800", color: colors.primary, marginBottom: 4 },
  connectionTime: { fontSize: 14, fontWeight: "600", color: colors.textSecondary },
  listHeader: { marginBottom: 16 },
  listTitle: { fontSize: 18, fontWeight: "800", color: colors.primary },
  recordCard: {
    backgroundColor: "#fff",
    borderRadius: 16,
    padding: 16,
    flexDirection: "row",
    alignItems: "center",
    marginBottom: 12,
    elevation: 1
  },
  recordIcon: {
    width: 48,
    height: 48,
    borderRadius: 12,
    backgroundColor: "#f8fafc",
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
  },
  recordDetails: { flex: 1 },
  recordType: { fontSize: 15, fontWeight: "800", color: colors.primary, marginBottom: 4 },
  recordStatus: { fontSize: 13, fontWeight: "600", color: colors.textSecondary },
  emptyState: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    padding: 32,
    alignItems: "center",
    marginBottom: 24,
    borderWidth: 1,
    borderColor: "#f1f5f9",
  },
  emptyStateTitle: { fontSize: 18, fontWeight: "800", color: colors.primary, marginBottom: 8 },
  emptyStateText: { fontSize: 14, color: colors.textSecondary, textAlign: "center" },
  syncButton: {
    backgroundColor: colors.accent,
    borderRadius: 16,
    paddingVertical: 18,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    elevation: 4,
  },
  syncButtonDisabled: { backgroundColor: "#94a3b8" },
  syncButtonText: { color: colors.surface, fontSize: 18, fontWeight: "800", marginLeft: 12 },
});
