import React, { useState, useMemo } from "react";
import {
  View,
  Text,
  StyleSheet,
  FlatList,
  TextInput,
  TouchableOpacity,
  ActivityIndicator,
  ScrollView,
  Alert
} from "react-native";
import { Feather } from "@expo/vector-icons";
import { colors as baseColors } from "../theme/colors";
import { useAuditorias } from "../domain/useCases/useAuditorias";
import { useTheme } from "../theme/ThemeContext";
import { apiClient } from "../data/api/apiClient";

export default function AuditoriasScreen({ navigation }) {
  const [search, setSearch] = useState("");
  // Filtros: 'Todas', 'Por comenzar', 'A punto de finalizar', 'Pendientes', 'En Progreso', 'Completadas'
  const [filtroActivo, setFiltroActivo] = useState("Todas");
  
  const { colors, isDark } = useTheme();
  const styles = React.useMemo(() => getStyles(colors, isDark), [colors, isDark]);
  const { data, isLoading, error, refetch } = useAuditorias();

  // Helper para clasificación temporal
  const getTimeLabel = (fecha_inicio, fecha_fin) => {
    if (!fecha_inicio || !fecha_fin) return { label: "Sin fecha", color: "#94a3b8", id: "neutral" };
    
    // Asumimos formato YYYY-MM-DD
    const msPerDay = 1000 * 60 * 60 * 24;
    const current = new Date();
    current.setHours(0,0,0,0);
    const start = new Date(fecha_inicio + "T00:00:00");
    const end = new Date(fecha_fin + "T00:00:00");

    const diffStart = (start - current) / msPerDay;
    const diffEnd = (end - current) / msPerDay;

    if (diffStart > 0) return { label: "Por comenzar", color: "#6366f1", id: "Por comenzar" }; // Indigo
    if (diffEnd < 0) return { label: "Atrasada", color: colors.danger, id: "Atrasada" };
    if (diffEnd <= 3) return { label: "Al Límite", color: "#f97316", id: "A punto de finalizar" }; // Naranja
    return { label: "A tiempo", color: colors.success, id: "Pendientes" };
  };

  const getStatusColor = (estado) => {
    switch (estado) {
      case "Pendiente": return { bg: colors.warningBg, text: colors.warning };
      case "En Progreso": return { bg: colors.infoBg, text: colors.info };
      case "Completada": return { bg: colors.successBg, text: colors.success };
      default: return { bg: colors.background, text: colors.textSecondary };
    }
  };

  // Filtrado Compuesto (Búsqueda textual + Chips de filtro)
  const filteredData = useMemo(() => {
    return data.filter(item => {
      // 1. Text Search
      const textMatch = item.folio.toLowerCase().includes(search.toLowerCase()) || 
                        item.ubicacion_nombre.toLowerCase().includes(search.toLowerCase());
      if (!textMatch) return false;

      // 2. Chip Filter Search
      if (filtroActivo === "Todas") return true;
      if (filtroActivo === "En Progreso" || filtroActivo === "Completadas") {
        const checkMap = filtroActivo === "Completadas" ? "completada" : "en progreso";
        return item.estado.toLowerCase() === checkMap;
      }

      // 3. Time status filters
      if (item.estado.toLowerCase() === "completada" && filtroActivo !== "Todas" && filtroActivo !== "Completadas") {
        return false;
      }
      
      const timeInfo = getTimeLabel(item.fecha_inicio, item.fecha_fin);
      if (filtroActivo === "Por comenzar" && timeInfo.id === "Por comenzar") return true;
      if (filtroActivo === "A punto de finalizar" && timeInfo.id === "A punto de finalizar") return true;
      if (filtroActivo === "Pendientes" && (timeInfo.id === "Pendientes" || timeInfo.id === "Atrasada" || timeInfo.id === "A punto de finalizar")) return true;

      return false;
    });
  }, [data, search, filtroActivo]);

  const renderItem = ({ item }) => {
    const statusStyle = getStatusColor(item.estado);
    const timeInfo = getTimeLabel(item.fecha_inicio, item.fecha_fin);
    let startText = item.escaneados > 0 ? "Reanudar Escaneo" : "Iniciar Escaneo";
    let isDisabled = false;
    let buttonColor = colors.accent;
    let iconName = item.escaneados > 0 ? "play-circle" : "maximize";

    if (timeInfo.id === "Por comenzar") {
      startText = "Aún no disponible";
      isDisabled = true;
      buttonColor = "#94a3b8"; // Gris
      iconName = "lock";
    } else if (timeInfo.id === "Atrasada") {
      startText = "Solicitar prórroga";
      isDisabled = true; 
      buttonColor = colors.danger; // Rojo 
      iconName = "alert-triangle";
    }

    // Format Dates safely
    const formatSmall = (d) => {
        if(!d) return "--";
        const parts = d.split("-");
        if(parts.length!==3) return d;
        return `${parts[2]}/${parts[1]}`;
    };

    return (
      <View style={styles.card}>
        <View style={styles.cardHeader}>
          <Text style={styles.folioText}>{item.folio}</Text>
          <View style={[styles.statusBadge, { backgroundColor: statusStyle.bg }]}>
            <Text style={[styles.statusText, { color: statusStyle.text }]}>
              {item.estado}
            </Text>
          </View>
        </View>

        <View style={styles.cardBody}>
          <View style={styles.infoRow}>
            <Feather name="map-pin" size={16} color={colors.textSecondary} />
            <Text style={styles.infoText}>{item.ubicacion_nombre}</Text>
          </View>
          
          <View style={[styles.infoRow, { justifyContent: 'space-between', paddingRight: 10 }]}>
            <View style={{flexDirection:'row', alignItems:'center'}}>
              <Feather name="calendar" size={16} color={colors.textSecondary} />
              <Text style={styles.infoText}>
                {formatSmall(item.fecha_inicio)} al {formatSmall(item.fecha_fin)}
              </Text>
            </View>
            
            {/* Etiqueta dinámica de tiempo */}
            {item.estado !== "Completada" && (
                <View style={[styles.timeLabelBox, { borderColor: timeInfo.color }]}>
                    <Text style={[styles.timeLabelText, { color: timeInfo.color }]}>{timeInfo.label}</Text>
                </View>
            )}
          </View>
          
          <View style={styles.infoRow}>
            <Feather name="check-square" size={16} color={colors.textSecondary} />
            <Text style={styles.infoText}>
              Progreso: {item.progreso} activos
            </Text>
          </View>
        </View>

        {item.estado !== "Completada" && (
          <TouchableOpacity
            style={[styles.cardButton, { backgroundColor: buttonColor, opacity: isDisabled && timeInfo.id !== "Atrasada" ? 0.9 : 1 }]}
            activeOpacity={0.7}
            onPress={async () => {
              if (timeInfo.id === "Por comenzar") return;
              if (timeInfo.id === "Atrasada") {
                try {
                   await apiClient("/buzon/", {
                      method: "POST",
                      body: JSON.stringify({
                         activo: "Auditoría " + item.folio,
                         tipo: "Solicitud de Prórroga",
                         reportado_por: item.usuario_nombre || "Auditor Autorizado",
                         area: item.ubicacion_nombre,
                         descripcion: "Solicitud de tiempo extendido para completar la auditoría.",
                         foto: null
                      })
                   });
                   Alert.alert("Éxito", "Se ha enviado la solicitud de prórroga al administrador mediante el buzón de incidencias.");
                } catch (e) {
                   Alert.alert("Error", "No se pudo enviar la solicitud. Verifica tu conexión.");
                }
                return;
              }
              
              navigation.navigate("Escaner", {
                auditoriaContext: {
                  id: item.id,
                  folio: item.folio,
                  escaneados: item.escaneados,
                  total: item.total_esperados,
                  ubicacion_nombre: item.ubicacion_nombre
                }
              });
            }}
          >
            <Feather name={iconName} size={18} color={colors.surface} />
            <Text style={styles.cardButtonText}>{startText}</Text>
          </TouchableOpacity>
        )}
      </View>
    );
  };

  const Chips = ["Todas", "Por comenzar", "Pendientes", "En Progreso", "A punto de finalizar", "Completadas"];

  if (isLoading && data.length === 0) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color={colors.accent} />
        <Text style={{ marginTop: 12, color: colors.textSecondary }}>Cargando auditorías asignadas...</Text>
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Feather name="alert-circle" size={48} color={colors.danger} />
        <Text style={{ marginTop: 16, color: colors.danger }}>{error}</Text>
        <TouchableOpacity style={styles.retryBtn} onPress={refetch}>
          <Text style={{ color: colors.surface }}>Reintentar</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.searchContainer}>
        <Feather name="search" size={20} color={colors.textSecondary} style={styles.searchIcon} />
        <TextInput
          style={styles.searchInput}
          placeholder="Buscar folio o lugar..."
          placeholderTextColor={colors.textSecondary}
          value={search}
          onChangeText={setSearch}
        />
      </View>

      <View style={styles.chipsWrapper}>
        <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={styles.chipsScroll}>
          {Chips.map(chip => (
             <TouchableOpacity 
                key={chip} 
                style={[styles.chip, filtroActivo === chip && styles.chipActive]}
                onPress={() => setFiltroActivo(chip)}
             >
                <Text style={[styles.chipText, filtroActivo === chip && styles.chipTextActive]}>{chip}</Text>
             </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      <FlatList
        data={filteredData}
        keyExtractor={(item) => String(item.id)}
        renderItem={renderItem}
        contentContainerStyle={styles.listContainer}
        showsVerticalScrollIndicator={false}
        refreshing={isLoading}
        onRefresh={refetch}
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Text style={{ color: colors.textSecondary }}>No existen auditorías con este filtro.</Text>
          </View>
        }
      />
    </View>
  );
}

const getStyles = (colors, isDark) => StyleSheet.create({
  container: { flex: 1, backgroundColor: colors.background },
  centerContainer: { flex: 1, justifyContent: "center", alignItems: "center", backgroundColor: colors.background },
  retryBtn: { marginTop: 16, backgroundColor: colors.accent, padding: 12, borderRadius: 8 },
  emptyContainer: { padding: 40, alignItems: "center" },
  
  searchContainer: {
    flexDirection: "row",
    alignItems: "center",
    backgroundColor: colors.surface,
    marginHorizontal: 24,
    marginTop: 20,
    marginBottom: 12,
    paddingHorizontal: 16,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: isDark ? colors.border : "#e2e8f0",
    height: 52,
  },
  searchIcon: { marginRight: 12 },
  searchInput: { flex: 1, fontSize: 16, color: colors.textPrimary },

  chipsWrapper: { height: 44, marginBottom: 12 },
  chipsScroll: { paddingHorizontal: 24, gap: 10, alignItems: 'center' },
  chip: {
      paddingHorizontal: 16,
      paddingVertical: 8,
      borderRadius: 100,
      backgroundColor: isDark ? "rgba(255,255,255,0.05)" : "#f1f5f9",
      borderWidth: 1,
      borderColor: isDark ? colors.border : "#e2e8f0"
  },
  chipActive: {
      backgroundColor: colors.accent,
      borderColor: colors.accent
  },
  chipText: {
      fontSize: 13,
      fontWeight: "600",
      color: colors.textSecondary
  },
  chipTextActive: {
      color: colors.surface
  },

  listContainer: { paddingHorizontal: 24, paddingBottom: 40 },
  card: {
    backgroundColor: isDark ? "rgba(255,255,255,0.02)" : colors.surface,
    borderRadius: 16,
    padding: 20,
    marginBottom: 16,
    borderWidth: 1,
    borderColor: isDark ? colors.border : "#f1f5f9",
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: isDark ? 0.3 : 0.05,
    shadowRadius: 8,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: "row",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: 14,
  },
  folioText: { fontSize: 16, fontWeight: "800", color: colors.textPrimary },
  statusBadge: { paddingHorizontal: 12, paddingVertical: 4, borderRadius: 12 },
  statusText: { fontSize: 12, fontWeight: "700" },
  cardBody: { marginBottom: 14 },
  infoRow: { flexDirection: "row", alignItems: "center", marginBottom: 8 },
  infoText: {
    fontSize: 14,
    color: colors.textSecondary,
    marginLeft: 8,
    fontWeight: "500",
  },
  timeLabelBox: {
    borderWidth: 1,
    paddingHorizontal: 8,
    paddingVertical: 3,
    borderRadius: 6,
    backgroundColor: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(255,255,255,0.5)'
  },
  timeLabelText: {
    fontSize: 10,
    fontWeight: "800",
    textTransform: "uppercase"
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
