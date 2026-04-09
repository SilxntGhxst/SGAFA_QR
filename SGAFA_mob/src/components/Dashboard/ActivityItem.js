import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { Feather } from "@expo/vector-icons";
import { colors } from "../../theme/colors";
import { useTheme } from "../../theme/ThemeContext";

const renderActivityIcon = (tipo, colors) => {
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

const getBadgeStyle = (estado, colors) => {
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

export default function ActivityItem({ item, onPress, hideBorder = false }) {
  const { colors, isDark } = useTheme();
  const styles = React.useMemo(() => getStyles(colors, isDark), [colors, isDark]);
  const badgeStyle = getBadgeStyle(item.estado, colors);
  
  return (
    <TouchableOpacity
      onPress={onPress}
      style={[styles.activityContainer, hideBorder && styles.activityContainerNoBorder]}
    >
      <View style={[styles.activityIconWrapper, { backgroundColor: colors.backgroundSecondary }]}>
        {renderActivityIcon(item.tipo, colors)}
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
    </TouchableOpacity>
  );
}

const getStyles = (colors, isDark) => StyleSheet.create({
  activityContainer: {
    flexDirection: "row",
    paddingVertical: 16,
    paddingHorizontal: 8,
    borderBottomWidth: 1,
    borderBottomColor: isDark ? colors.border : "#f1f5f9",
    alignItems: "center",
  },
  activityContainerNoBorder: { borderBottomWidth: 0 },
  activityIconWrapper: {
    width: 40,
    height: 40,
    borderRadius: 12,
    justifyContent: "center",
    alignItems: "center",
    marginRight: 16,
    backgroundColor: isDark ? "rgba(255,255,255,0.05)" : "#f8fafc",
    borderWidth: 1,
    borderColor: isDark ? colors.border : 'transparent'
  },
  activityDetails: { flex: 1, justifyContent: "center" },
  activityTitle: {
    fontSize: 14,
    fontWeight: "700",
    color: colors.textPrimary,
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
