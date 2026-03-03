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
  ScrollView
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { colors } from '../theme/colors';

export default function RegisterScreen({ navigation }) {
  const [nombres, setNombres] = useState('');
  const [apellidos, setApellidos] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  return (
    <SafeAreaView style={styles.safeArea}>
      <KeyboardAvoidingView 
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'} 
        style={styles.container}
      >
        <ScrollView 
          showsVerticalScrollIndicator={false}
          contentContainerStyle={styles.scrollContainer}
        >
          <View style={styles.formContainer}>
            
            {/* --- ZONA DE IDENTIDAD VISUAL --- */}
            <View style={styles.headerContainer}>
              <Image 
                source={require('../../assets/logo.png')} 
                style={styles.logo} 
                resizeMode="contain" 
              />
              <Text style={styles.title}>Únete a S.G.A.F.A</Text>
              <Text style={styles.subtitle}>Crea tu cuenta de resguardante</Text>
            </View>

            {/* --- ZONA DE FORMULARIO --- */}
            <View style={styles.inputGroup}>
              <Text style={styles.label}>Nombre/Nombres</Text>
              <TextInput
                style={styles.input}
                placeholder="Ej. Santiago"
                placeholderTextColor={colors.textSecondary}
                value={nombres}
                onChangeText={setNombres}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Apellidos</Text>
              <TextInput
                style={styles.input}
                placeholder="Tus apellidos"
                placeholderTextColor={colors.textSecondary}
                value={apellidos}
                onChangeText={setApellidos}
              />
            </View>

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

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Contraseña</Text>
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor={colors.textSecondary}
                secureTextEntry={true}
                value={password}
                onChangeText={setPassword}
              />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Confirmar contraseña</Text>
              <TextInput
                style={styles.input}
                placeholder="••••••••"
                placeholderTextColor={colors.textSecondary}
                secureTextEntry={true}
                value={confirmPassword}
                onChangeText={setConfirmPassword}
              />
            </View>

            {/* --- ZONA DE ACCIONES --- */}
            <TouchableOpacity style={styles.primaryButton}>
              <Text style={styles.primaryButtonText}>Crear cuenta</Text>
            </TouchableOpacity>

            <View style={styles.footerContainer}>
              <Text style={styles.footerText}>¿Ya tienes una cuenta? </Text>
              {/* Aquí aplicamos la navegación inversa (goBack) */}
              <TouchableOpacity onPress={() => navigation.goBack()}>
                <Text style={styles.secondaryActionText}>Inicia sesión</Text>
              </TouchableOpacity>
            </View>

          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  container: { flex: 1 },
  scrollContainer: { 
    flexGrow: 1, 
    justifyContent: 'center', 
    paddingVertical: 24 
  },
  formContainer: { 
    paddingHorizontal: 32, 
    width: '100%', 
    maxWidth: 400, 
    alignSelf: 'center' 
  },
  headerContainer: {
    alignItems: 'center',
    marginBottom: 32,
  },
  logo: {
    width: 120, // Reducido ligeramente para acomodar el formulario largo
    height: 120,
    marginBottom: 16,
  },
  title: { 
    fontSize: 26, 
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
  inputGroup: {
    marginBottom: 16,
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
    marginTop: 12,
    marginBottom: 24,
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
  footerContainer: { 
    flexDirection: 'row', 
    justifyContent: 'center', 
    alignItems: 'center',
    marginBottom: 16
  },
  footerText: { 
    color: colors.textSecondary, 
    fontSize: 14 
  },
  secondaryActionText: { 
    color: colors.accent, 
    fontSize: 14, 
    fontWeight: '700' 
  }
});