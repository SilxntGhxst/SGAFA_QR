import React from 'react';
import { View, Text, TouchableOpacity, StyleSheet } from 'react-native';
import { colors } from '../theme/colors';

export default function IncidenciaScreen({ navigation }) {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Interfaz: Reporte de Activo Fantasma/Dañado</Text>
      <TouchableOpacity style={styles.button} onPress={() => navigation.goBack()}>
        <Text style={styles.buttonText}>Cancelar y Volver</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.background },
  text: { fontSize: 18, color: colors.danger, fontWeight: 'bold', marginBottom: 20, textAlign: 'center', paddingHorizontal: 20 },
  button: { backgroundColor: colors.surface, padding: 16, borderRadius: 12, borderWidth: 1, borderColor: '#e2e8f0' },
  buttonText: { color: colors.textPrimary, fontWeight: 'bold' }
});