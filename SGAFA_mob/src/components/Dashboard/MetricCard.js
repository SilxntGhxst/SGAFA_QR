import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { Feather } from "@expo/vector-icons";
import { useTheme } from "../../theme/ThemeContext";

export default function MetricCard({ value, label }) {
  const { colors, isDark } = useTheme();
  const styles = React.useMemo(() => getStyles(colors, isDark), [colors, isDark]);

  return (
    <View style={styles.metricCard}>
      <View style={styles.iconContainer}>
        <Feather name="folder" size={24} color={colors.accent} />
      </View>
      <Text style={styles.metricValue}>{value}</Text>
      <Text style={styles.metricLabel}>{label}</Text>
    </View>
  );
}

const getStyles = (colors, isDark) => StyleSheet.create({
  metricCard: {
    backgroundColor: colors.surface,
    padding: 20,
    borderRadius: 20,
    flex: 1,
    marginHorizontal: 6,
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: isDark ? 0.05 : 0.08,
    shadowRadius: 12,
    elevation: 3,
    borderWidth: 1,
    borderColor: isDark ? colors.border : 'transparent',
  },
  iconContainer: {
    width: 48,
    height: 48,
    borderRadius: 16,
    backgroundColor: isDark ? "rgba(255,255,255,0.05)" : "#eff6ff",
    justifyContent: "center",
    alignItems: "center",
    marginBottom: 16,
  },
  metricValue: {
    fontSize: 28,
    fontWeight: "800",
    color: isDark ? colors.textPrimary : colors.primary,
    marginBottom: 4,
  },
  metricLabel: {
    fontSize: 13,
    fontWeight: "600",
    color: colors.textSecondary,
    lineHeight: 18,
  },
});
