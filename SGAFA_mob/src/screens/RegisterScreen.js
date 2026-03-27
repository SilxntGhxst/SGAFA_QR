import React, { useState } from 'react';
import {
  View, Text, TextInput, TouchableOpacity, StyleSheet,
  KeyboardAvoidingView, Platform, Image, ScrollView,
  ActivityIndicator, Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { colors } from '../theme/colors';
import { useAuth } from '../domain/AuthContext';

export default function RegisterScreen({ navigation }) {
  const [nombres,          setNombres]          = useState('');
  const [apellidos,        setApellidos]        = useState('');
  const [email,            setEmail]            = useState('');
  const [password,         setPassword]         = useState('');
  const [confirmPassword,  setConfirmPassword]  = useState('');
  const [loading,          setLoading]          = useState(false);

  const { register } = useAuth();

  const handleRegister = async () => {
    if (!nombres.trim() || !apellidos.trim() || !email.trim() || !password.trim()) {
      Alert.alert('Campos incompletos', 'Por favor, completa todos los campos.');
      return;
    }
    if (password !== confirmPassword) {
      Alert.alert('Error de seguridad', 'Las contraseñas no coinciden.');
      return;
    }
    if (password.length < 6) {
      Alert.alert('Contraseña débil', 'La contraseña debe tener al menos 6 caracteres.');
      return;
    }

    setLoading(true);
    try {
      await register(nombres.trim(), apellidos.trim(), email.trim().toLowerCase(), password);
      // registro + auto-login: AppNavigator redirige a Main
    } catch (err) {
      Alert.alert('Error al registrarse', err.message || 'No se pudo crear la cuenta.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <SafeAreaView style={styles.safeArea}>
      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
        <ScrollView showsVerticalScrollIndicator={false} contentContainerStyle={styles.scrollContainer}>
          <View style={styles.formContainer}>

            <View style={styles.headerContainer}>
              <Image source={require('../../assets/logo.png')} style={styles.logo} resizeMode="contain" />
              <Text style={styles.title}>Únete a S.G.A.F.A</Text>
              <Text style={styles.subtitle}>Crea tu cuenta de resguardante</Text>
            </View>

            {[
              { label: 'Nombre/Nombres', value: nombres,   setter: setNombres,   placeholder: 'Ej. Santiago' },
              { label: 'Apellidos',      value: apellidos, setter: setApellidos, placeholder: 'Tus apellidos' },
              { label: 'Correo electrónico', value: email, setter: setEmail, placeholder: 'ejemplo@correo.com', keyboard: 'email-address', lower: true },
              { label: 'Contraseña',        value: password,        setter: setPassword,        placeholder: '••••••••', secure: true },
              { label: 'Confirmar contraseña', value: confirmPassword, setter: setConfirmPassword, placeholder: '••••••••', secure: true },
            ].map(({ label, value, setter, placeholder, keyboard, lower, secure }) => (
              <View key={label} style={styles.inputGroup}>
                <Text style={styles.label}>{label}</Text>
                <TextInput
                  style={styles.input}
                  placeholder={placeholder}
                  placeholderTextColor={colors.textSecondary}
                  keyboardType={keyboard || 'default'}
                  autoCapitalize={lower ? 'none' : 'words'}
                  secureTextEntry={!!secure}
                  value={value}
                  onChangeText={setter}
                  editable={!loading}
                />
              </View>
            ))}

            <TouchableOpacity
              style={[styles.primaryButton, loading && styles.disabled]}
              onPress={handleRegister}
              disabled={loading}
            >
              {loading
                ? <ActivityIndicator color="#fff" />
                : <Text style={styles.primaryButtonText}>Crear cuenta</Text>
              }
            </TouchableOpacity>

            <View style={styles.footerContainer}>
              <Text style={styles.footerText}>¿Ya tienes una cuenta? </Text>
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
  safeArea:        { flex: 1, backgroundColor: colors.background },
  container:       { flex: 1 },
  scrollContainer: { flexGrow: 1, justifyContent: 'center', paddingVertical: 24 },
  formContainer:   { paddingHorizontal: 32, width: '100%', maxWidth: 400, alignSelf: 'center' },
  headerContainer: { alignItems: 'center', marginBottom: 32 },
  logo:            { width: 120, height: 120, marginBottom: 16 },
  title:           { fontSize: 26, fontWeight: '800', color: colors.primary, letterSpacing: 0.5 },
  subtitle:        { fontSize: 14, fontWeight: '400', color: colors.textSecondary, marginTop: 4 },
  inputGroup:      { marginBottom: 16 },
  label:           { fontSize: 13, fontWeight: '600', color: colors.textPrimary, marginBottom: 8, textTransform: 'uppercase', letterSpacing: 0.5 },
  input:           { backgroundColor: colors.surface, borderWidth: 1, borderColor: '#e2e8f0', borderRadius: 12, paddingHorizontal: 16, height: 52, fontSize: 16, color: colors.textPrimary },
  primaryButton:   { backgroundColor: colors.accent, borderRadius: 12, height: 52, justifyContent: 'center', alignItems: 'center', marginTop: 12, marginBottom: 24, shadowColor: colors.accent, shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.2, shadowRadius: 8, elevation: 4 },
  disabled:        { opacity: 0.6 },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: '700' },
  footerContainer: { flexDirection: 'row', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  footerText:      { color: colors.textSecondary, fontSize: 14 },
  secondaryActionText: { color: colors.accent, fontSize: 14, fontWeight: '700' },
});
