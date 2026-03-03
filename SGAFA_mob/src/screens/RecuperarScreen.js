import React, { useState } from 'react';
import { 
  View, 
  Text, 
  TextInput, 
  TouchableOpacity, 
  StyleSheet, 
  KeyboardAvoidingView, 
  Platform, 
  Image, 
  Alert // Importamos Alert para la simulación
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { SafeAreaProvider } from 'react-native-safe-area-context';
import { colors } from '../theme/colors';

export default function RecuperarScreen({ navigation }) {
  const [email, setEmail] = useState('');

  // Simulación de envío de enlace/código
  const handleRecover = () => {
    if (!email.trim() || !email.includes('@')) {
      Alert.alert(
        "Error",
        "Por favor, ingresa un correo electrónico válido."
      );
      return;
    }

    // Simulamos la respuesta exitosa del servidor
    Alert.alert(
      "Enlace Enviado",
      "Hemos enviado un enlace de recuperación a tu correo electrónico. Revisa tu bandeja de entrada y la carpeta de spam.",
      [
        { 
          text: "OK", 
          onPress: () => navigation.goBack() // Regresamos al login al aceptar
        }
      ]
    );
  };

  return (
    <SafeAreaProvider>
      <SafeAreaView style={styles.safeArea}>
        <KeyboardAvoidingView 
          behavior={Platform.OS === 'ios' ? 'padding' : 'height'} 
          style={styles.container}
        >
          <View style={styles.formContainer}>
            
            {/* --- ZONA DE IDENTIDAD VISUAL --- */}
            <View style={styles.headerContainer}>
              <Image 
                source={require('../../assets/logo.png')} 
                style={styles.logo} 
                resizeMode="contain" 
              />
              <Text style={styles.title}>Recuperar</Text>
              <Text style={styles.subtitle}>S.G.A.F.A QR</Text>
            </View>

            {/* --- ZONA INSTRUCTIVA (Detalles para completar la interfaz) --- */}
            <View style={styles.instructionsContainer}>
              <Text style={styles.instructionsText}>
                Ingresa tu correo electrónico asociado a tu cuenta. Te enviaremos un enlace para que puedas restablecer tu contraseña.
              </Text>
            </View>

            {/* --- ZONA DE FORMULARIO --- */}
            <View style={styles.inputGroup}>
              <Text style={styles.label}>Correo electrónico</Text>
              <TextInput
                style={styles.input}
                placeholder="ejemplo@correo.com"
                placeholderTextColor={colors.textSecondary}
                keyboardType="email-address"
                autoCapitalize="none"
                value={email}
                onChangeText={setEmail}
              />
            </View>

            {/* --- ZONA DE ACCIONES --- */}
            <TouchableOpacity 
              style={styles.primaryButton}
              onPress={handleRecover} // Conectamos la simulación
            >
              <Text style={styles.primaryButtonText}>Enviar Enlace</Text>
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.secondaryButton}
              onPress={() => navigation.goBack()} // Navegación inversa
            >
              <Text style={styles.secondaryButtonText}>Cancelar</Text>
            </TouchableOpacity>

          </View>
        </KeyboardAvoidingView>
      </SafeAreaView>
    </SafeAreaProvider>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  container: { flex: 1, justifyContent: 'center' },
  formContainer: { 
    paddingHorizontal: 32, 
    width: '100%', 
    maxWidth: 400, 
    alignSelf: 'center' 
  },
  headerContainer: {
    alignItems: 'center',
    marginBottom: 24,
  },
  logo: {
    width: 200, 
    height: 200,
    marginBottom: 16,
  },
  title: { 
    fontSize: 28, 
    fontWeight: '800', 
    color: colors.primary, 
    letterSpacing: 0.5,
  },
  subtitle: { 
    fontSize: 14, 
    fontWeight: '400', 
    color: colors.textSecondary,
    marginTop: 4,
  },
  instructionsContainer: {
    marginBottom: 32,
    backgroundColor: '#eff6ff', // Un azul muy tenue para destacar las instrucciones
    padding: 16,
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#dbeafe'
  },
  instructionsText: {
    fontSize: 14,
    color: colors.accent,
    textAlign: 'center',
    lineHeight: 20,
    fontWeight: '500'
  },
  inputGroup: {
    marginBottom: 32,
  },
  label: { 
    fontSize: 13, 
    fontWeight: '600', 
    color: colors.textPrimary, 
    marginBottom: 8,
    textTransform: 'uppercase',
    letterSpacing: 0.5,
  },
  input: { 
    backgroundColor: colors.surface, 
    borderWidth: 1, 
    borderColor: '#e2e8f0', 
    borderRadius: 12, 
    paddingHorizontal: 16, 
    height: 52, 
    fontSize: 16, 
    color: colors.textPrimary,
  },
  primaryButton: { 
    backgroundColor: colors.accent, 
    borderRadius: 12, 
    height: 52, 
    justifyContent: 'center', 
    alignItems: 'center', 
    marginBottom: 16,
    // Sombra sutil para diseño minimalista pero moderno
    shadowColor: colors.accent,
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.2,
    shadowRadius: 8,
    elevation: 4,
  },
  primaryButtonText: { 
    color: colors.surface, 
    fontSize: 16, 
    fontWeight: '700' 
  },
  secondaryButton: { 
    height: 52, 
    justifyContent: 'center', 
    alignItems: 'center' 
  },
  secondaryButtonText: { 
    color: colors.textSecondary, 
    fontSize: 16, 
    fontWeight: '600' 
  }
});