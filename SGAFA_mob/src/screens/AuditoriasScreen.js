import React, { useState } from "react";
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TextInput,
  TouchableOpacity,
} from "react-native";
import { Feather } from "@expo/vector-icons";
import { colors } from "../theme/colors";

// --- API FALSA (Mock Data de Auditorías) ---
const mockAuditorias = [
  {
    id: "1",
    folio: "AUD-2026-001",
    ubicacion: "Laboratorio de Cómputo A",
    fecha: "Hoy, 09:00 AM",
    estado: "En Progreso",
    progreso: "12/45",
  },
  {
    id: "2",
    folio: "AUD-2026-002",
    ubicacion: "Oficinas Administrativas",
    fecha: "02 Mar 2026",
    estado: "Pendiente",
    progreso: "0/20",
  },
  {
    id: "3",
    folio: "AUD-2026-003",
    ubicacion: "Biblioteca Central",
    fecha: "28 Feb 2026",
    estado: "Completada",
    progreso: "150/150",
  },
];

export default function AuditoriasScreen({ navigation }) {
  const [search, setSearch] = useState("");

  // Renderizado dinámico de colores de estado
  // Renderizado dinámico conectado al theme global
  const getStatusColor = (estado) => {
    switch (estado) {
      case "Pendiente":
        return { bg: colors.warningBg, text: colors.warning };
      case "En Progreso":
        return { bg: colors.infoBg, text: colors.info };
      case "Completada":
        return { bg: colors.successBg, text: colors.success };
      default:
        return { bg: colors.background, text: colors.textSecondary };
    }
  };

  const renderItem = ({ item }) => {
    const statusStyle = getStatusColor(item.estado);

    return (
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.folioText}>{item.folio}</Text>
          <View
            style={[styles.statusBadge, { backgroundColor: statusStyle.bg }]}
          >
            <Text style={[styles.statusText, { color: statusStyle.text }]}>
              {item.estado}
            </Text>
          </View>
        </View>

        <View style={styles.cardBody}>
          <View style={styles.infoRow}>
            <Feather name="map-pin" size={16} color={colors.textSecondary} />
            <Text style={styles.infoText}>{item.ubicacion}</Text>
          </View>
          <View style={styles.infoRow}>
            <Feather name="calendar" size={16} color={colors.textSecondary} />
            <Text style={styles.infoText}>{item.fecha}</Text>
          </View>
          <View style={styles.infoRow}>
            <Feather
              name="check-square"
              size={16}
              color={colors.textSecondary}
            />
            <Text style={styles.infoText}>
              Progreso: {item.progreso} activos
            </Text>
          </View>
        </View>

        {/* Botón de acción: Si está completada, no mostramos el botón de escanear */}
        {item.estado !== "Completada" && (
          <TouchableOpacity
            style={styles.cardButton}
            onPress={() => navigation.navigate("Escaner")} // <--- Navegación al Escáner
          >
            <Feather name="maximize" size={18} color={colors.surface} />
            <Text style={styles.cardButtonText}>Reanudar Escaneo</Text>
          </TouchableOpacity>
        )}
      </View>
    );
  };

  return (
    <View style={styles.container}>
      {/* Barra de Búsqueda */}
      <View style={styles.searchContainer}>
        <Feather
          name="search"
          size={20}
          color={colors.textSecondary}
          style={styles.searchIcon}
        />
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar folio o ubicación..."
          placeholderTextColor={colors.textSecondary}
          value={search}
          onChangeText={setSearch}
        />
      </View>

      {/* Lista de Auditorías */}
      <FlatList
        data={mockAuditorias}
        keyExtractor={(item) => item.id}
        renderItem={renderItem}
        contentContainerStyle={styles.listContainer}
        showsVerticalScrollIndicator={false}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.background },
  searchContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: colors.surface,
    margin: 24,
    paddingHorizontal: 16,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: "#e2e8f0",
    height: 52,
  },
  searchIcon: { marginRight: 12 },
  searchInput: { flex: 1, fontSize: 16, color: colors.textPrimary },
  listContainer: { paddingHorizontal: 24, paddingBottom: 40 },
  card: {
    backgroundColor: colors.surface,
    borderRadius: 16,
    padding: 20,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: "#f1f5f9",
    shadowColor: colors.primary,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.05,
    shadowRadius: 8,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 16,
  },
  folioText: { fontSize: 16, fontWeight: "800", color: colors.primary },
  statusBadge: { paddingHorizontal: 12, paddingVertical: 4, borderRadius: 12 },
  statusText: { fontSize: 12, fontWeight: "700" },
  cardBody: { marginBottom: 16 },
  infoRow: { flexDirection: "row", alignItems: "center", marginBottom: 8 },
  infoText: {
    fontSize: 14,
    color: colors.textSecondary,
    marginLeft: 8,
    fontWeight: "500",
  },
  cardButton: {
    backgroundColor: colors.accent,
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    paddingVertical: 12,
    borderRadius: 8,
  },
  cardButtonText: {
    color: colors.surface,
    fontSize: 14,
    fontWeight: "700",
    marginLeft: 8,
  },
});
