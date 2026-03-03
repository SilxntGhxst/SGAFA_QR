import React from "react";
import { View, Text, TouchableOpacity, StyleSheet } from "react-native";
import { colors } from "../theme/colors";

export default function EscanerScreen({ navigation }) {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Interfaz de Cámara / Escáner QR</Text>
      <TouchableOpacity
        style={styles.button}
        onPress={() => navigation.goBack()}
      >
        <Text style={styles.buttonText}>Cancelar y Volver</Text>
      </TouchableOpacity>
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
  text: {
    fontSize: 18,
    color: colors.primary,
    fontWeight: "bold",
    marginBottom: 20,
  },
  button: { backgroundColor: colors.danger, padding: 16, borderRadius: 12 },
  buttonText: { color: colors.surface, fontWeight: "bold" },
});
