import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { Feather } from "@expo/vector-icons";
import { colors } from "../../theme/colors";

const renderActivityIcon = (tipo) => {
  switch (tipo) {
    case "alerta": // Relacionado a advertencias o pendientes graves
      return <Feather name="clock" size={20} color={colors.warning} />;
    case "exito": // Relacionado a acciones exitosas o escaneos
      return <Feather name="check-circle" size={20} color={colors.success} />;
    case "aviso": // Relacionado a daños o problemas críticos
      return <Feather name="alert-triangle" size={20} color={colors.danger} />;
    default:
      return <Feather name="info" size={20} color={colors.info} />;
  }
};

const getBadgeStyle = (estado) => {
  const estStr = (estado || "").toLowerCase().trim();
  
  if (estStr === "pendiente" || estStr === "en progreso") {
    return { bg: colors.warningBg, text: colors.warning };
  } else if (estStr === "resuelto" || estStr === "escaneado" || estStr === "funcional") {
    return { bg: colors.successBg, text: colors.success };
  } else if (estStr === "en revisión" || estStr === "asignado" || estStr === "en mantenimiento") {
    return { bg: colors.infoBg, text: colors.info };
  } else if (estStr === "dañado" || estStr === "faltante" || estStr.includes("reporte")) {
    return { bg: colors.dangerBg, text: colors.danger };
  }
  
  return { bg: colors.infoBg, text: colors.info };
};

export default function ActivityItem({ item }) {
  // Ahora tomamos el estilo del estado (estado) en vez del tipo
  const badgeStyle = getBadgeStyle(item.estado);
  
  return (
    <View style={styles.activityItem}>
      <View style={styles.activityIconContainer}>
        {renderActivityIcon(item.tipo)}
      </View>
      <View style={styles.activityDetails}>
        <Text style={styles.activityTitle}>{item.titulo}</Text>
        <Text style={styles.activityDate}>{item.fecha}</Text>
      </View>
      <View style={styles.activityStatus}>
        <View style={[styles.statusBadge, { backgroundColor: badgeStyle.bg }]}>
          <Text style={[styles.statusText, { color: badgeStyle.text }]}>
            {item.estado}
          </Text>
        </View>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
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
  activityDetails: { flex: 1, justifyContent: "center" },
  activityTitle: {
    fontSize: 14,
    fontWeight: "700",
    color: colors.primary,
  },
  activityDate: {
    fontSize: 12,
    color: colors.textSecondary,
    marginTop: 4,
  },
  activityStatus: { paddingLeft: 8 },
  statusBadge: {
    paddingHorizontal: 10,
    paddingVertical: 4,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
  },
  statusText: { fontSize: 11, fontWeight: "800", textTransform: "uppercase" },
});
