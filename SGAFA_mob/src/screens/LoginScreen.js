import React, { useState } from 'react';
import {
  View, Text, TextInput, TouchableOpacity,
  StyleSheet, KeyboardAvoidingView, Platform,
  Image, ActivityIndicator, Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { colors } from '../theme/colors';
import { useAuth } from '../domain/AuthContext';

export default function LoginScreen({ navigation }) {
  const [email, setEmail]       = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading]   = useState(false);

  const { login } = useAuth();

  const handleLogin = async () => {
    if (!email.trim() || !password.trim()) {
      Alert.alert('Campos requeridos', 'Por favor ingresa tu correo y contraseña.');
      return;
    }
    setLoading(true);
    try {
      await login(email.trim().toLowerCase(), password);
      // El AuthContext actualiza `user`, AppNavigator redirige automáticamente a Main
    } catch (err) {
      Alert.alert('Error de acceso', err.message || 'No se pudo iniciar sesión.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.safeArea}>
      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        style={styles.container}
      >
        <View style={styles.formContainer}>

          {/* --- IDENTIDAD VISUAL --- */}
          <View style={styles.headerContainer}>
            <Image
              source={require('../../assets/logo.png')}
              style={styles.logo}
              resizeMode="contain"
            />
            <Text style={styles.title}>S.G.A.F.A QR</Text>
            <Text style={styles.subtitle}>Gestión de Activos Fijos</Text>
          </View>

          {/* --- FORMULARIO --- */}
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
              editable={!loading}
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
              editable={!loading}
            />
          </View>

          <TouchableOpacity
            style={styles.forgotPasswordContainer}
            onPress={() => navigation.navigate('Recuperar')}
          >
            <Text style={styles.forgotPasswordText}>¿Olvidaste tu contraseña?</Text>
          </TouchableOpacity>

          {/* --- BOTÓN LOGIN --- */}
          <TouchableOpacity
            style={[styles.primaryButton, loading && styles.primaryButtonDisabled]}
            onPress={handleLogin}
            disabled={loading}
          >
            {loading
              ? <ActivityIndicator color="#fff" />
              : <Text style={styles.primaryButtonText}>Iniciar sesión</Text>
            }
          </TouchableOpacity>

          <View style={styles.footerContainer}>
            <Text style={styles.footerText}>¿No tienes una cuenta? </Text>
            <TouchableOpacity onPress={() => navigation.navigate('Register')}>
              <Text style={styles.secondaryActionText}>Regístrate aquí</Text>
            </TouchableOpacity>
          </View>

        </View>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea:       { flex: 1, backgroundColor: colors.background },
  container:      { flex: 1, justifyContent: 'center' },
  formContainer:  { paddingHorizontal: 32, width: '100%', maxWidth: 400, alignSelf: 'center' },
  headerContainer:{ alignItems: 'center', marginBottom: 48 },
  logo:           { width: 200, height: 200, marginBottom: 16 },
  title:          { fontSize: 28, fontWeight: '800', color: colors.primary, letterSpacing: 0.5 },
  subtitle:       { fontSize: 14, fontWeight: '400', color: colors.textSecondary, marginTop: 4 },
  inputGroup:     { marginBottom: 20 },
  label:          { fontSize: 13, fontWeight: '600', color: colors.textPrimary, marginBottom: 8, textTransform: 'uppercase', letterSpacing: 0.5 },
  input:          { backgroundColor: colors.surface, borderWidth: 1, borderColor: '#e2e8f0', borderRadius: 12, paddingHorizontal: 16, height: 52, fontSize: 16, color: colors.textPrimary },
  forgotPasswordContainer: { alignItems: 'flex-end', marginBottom: 32, marginTop: -4 },
  forgotPasswordText:      { color: colors.accent, fontSize: 14, fontWeight: '600' },
  primaryButton:         { backgroundColor: colors.accent, borderRadius: 12, height: 52, justifyContent: 'center', alignItems: 'center', marginBottom: 24, shadowColor: colors.accent, shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.2, shadowRadius: 8, elevation: 4 },
  primaryButtonDisabled: { opacity: 0.6 },
  primaryButtonText:     { color: colors.surface, fontSize: 16, fontWeight: '700' },
  footerContainer:       { flexDirection: 'row', justifyContent: 'center', alignItems: 'center' },
  footerText:            { color: colors.textSecondary, fontSize: 14 },
  secondaryActionText:   { color: colors.accent, fontSize: 14, fontWeight: '700' },
});