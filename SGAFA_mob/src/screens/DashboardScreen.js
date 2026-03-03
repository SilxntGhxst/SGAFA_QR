import React from "react";
import { View, Text, StyleSheet } from "react-native";
import { colors } from "../theme/colors";

export default function DashboardScreen() {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Pantalla: Login</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center",
    alignItems: "center",
    backgroundColor: colors.background,
  },
  text: { fontSize: 20, color: colors.textPrimary, fontWeight: "bold" },
});
