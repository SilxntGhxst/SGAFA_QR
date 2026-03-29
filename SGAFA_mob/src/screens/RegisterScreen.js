import React, { useState, useMemo } from 'react';
import {
  View, Text, TextInput, TouchableOpacity, StyleSheet,
  KeyboardAvoidingView, Platform, Image, ScrollView,
  ActivityIndicator, Alert,
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../theme/colors';
import { useAuth } from '../domain/AuthContext';

export default function RegisterScreen({ navigation }) {
  const [nombres,          setNombres]          = useState('');
  const [apellidos,        setApellidos]        = useState('');
  const [email,            setEmail]            = useState('');
  const [password,         setPassword]         = useState('');
  const [confirmPassword,  setConfirmPassword]  = useState('');
  const [showPassword,     setShowPassword]     = useState(false);
  const [showConfirm,      setShowConfirm]      = useState(false);
  const [loading,          setLoading]          = useState(false);

  const { register } = useAuth();

  // Password Strength Logic
  const strength = useMemo(() => {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/\d/.test(password)) score++;
    return score;
  }, [password]);

  const handleRegister = async () => {
    if (!nombres.trim() || !apellidos.trim() || !email.trim() || !password.trim()) {
      Alert.alert('Campos incompletos', 'Por favor, completa todos los campos.');
      return;
    }
    if (password !== confirmPassword) {
      Alert.alert('Error de seguridad', 'Las contraseñas no coinciden.');
      return;
    }
    
    // Validar requerimientos
    if (password.length < 8) {
      Alert.alert('Seguridad', 'La contraseña debe tener al menos 8 caracteres.');
      return;
    }
    if (!/[A-Z]/.test(password)) {
      Alert.alert('Seguridad', 'La contraseña debe incluir al menos una mayúscula.');
      return;
    }
    if (!/\d/.test(password)) {
      Alert.alert('Seguridad', 'La contraseña debe incluir al menos un número.');
      return;
    }

    setLoading(true);
    try {
      await register(nombres.trim(), apellidos.trim(), email.trim().toLowerCase(), password);
    } catch (err) {
      Alert.alert('Error al registrarse', err.message || 'No se pudo crear la cuenta.');
    } finally {
      setLoading(false);
    }
  };

  const renderRequirement = (text, met) => (
    <View style={styles.reqRow}>
      <Ionicons 
        name={met ? "checkmark-circle" : "ellipse-outline"} 
        size={14} 
        color={met ? "#10b981" : "#94a3b8"} 
      />
      <Text style={[styles.reqText, met && { color: '#10b981', fontWeight: '600' }]}>{text}</Text>
    </View>
  );

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

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Nombre/Nombres</Text>
              <TextInput style={styles.input} placeholder="Ej. Santiago" value={nombres} onChangeText={setNombres} editable={!loading} />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Apellidos</Text>
              <TextInput style={styles.input} placeholder="Tus apellidos" value={apellidos} onChangeText={setApellidos} editable={!loading} />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Correo electrónico</Text>
              <TextInput style={styles.input} placeholder="ejemplo@correo.com" value={email} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" editable={!loading} />
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Contraseña</Text>
              <View style={styles.inputWithIcon}>
                <TextInput
                  style={[styles.input, { flex: 1, borderTopRightRadius: 0, borderBottomRightRadius: 0, borderRightWidth: 0 }]}
                  placeholder="••••••••"
                  secureTextEntry={!showPassword}
                  value={password}
                  onChangeText={setPassword}
                  editable={!loading}
                />
                <TouchableOpacity 
                  style={styles.eyeIcon} 
                  onPress={() => setShowPassword(!showPassword)}
                >
                  <Ionicons name={showPassword ? "eye-off" : "eye"} size={22} color="#94a3b8" />
                </TouchableOpacity>
              </View>

              {/* Password Strength */}
              {password.length > 0 && (
                <View style={{ marginTop: 8 }}>
                  <View style={styles.strengthWrapper}>
                    <View style={[styles.strengthBar, { width: `${(strength / 3) * 100}%`, backgroundColor: strength === 3 ? '#10b981' : strength === 2 ? '#f59e0b' : '#ef4444' }]} />
                  </View>
                  <View style={styles.reqContainer}>
                    {renderRequirement('8+ caracteres', password.length >= 8)}
                    {renderRequirement('Mayúscula', /[A-Z]/.test(password))}
                    {renderRequirement('Un número', /\d/.test(password))}
                  </View>
                </View>
              )}
            </View>

            <View style={styles.inputGroup}>
              <Text style={styles.label}>Confirmar contraseña</Text>
              <View style={styles.inputWithIcon}>
                <TextInput
                  style={[styles.input, { flex: 1, borderTopRightRadius: 0, borderBottomRightRadius: 0, borderRightWidth: 0 }]}
                  placeholder="••••••••"
                  secureTextEntry={!showConfirm}
                  value={confirmPassword}
                  onChangeText={setConfirmPassword}
                  editable={!loading}
                />
                <TouchableOpacity 
                  style={styles.eyeIcon} 
                  onPress={() => setShowConfirm(!showConfirm)}
                >
                  <Ionicons name={showConfirm ? "eye-off" : "eye"} size={22} color="#94a3b8" />
                </TouchableOpacity>
              </View>
            </View>

            <TouchableOpacity
              style={[styles.primaryButton, (loading || (password.length > 0 && strength < 3)) && styles.disabled]}
              onPress={handleRegister}
              disabled={loading || (password.length > 0 && strength < 3)}
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
  label:           { fontSize: 12, fontWeight: '700', color: colors.textPrimary, marginBottom: 8, textTransform: 'uppercase', letterSpacing: 0.8 },
  input:           { backgroundColor: colors.surface, borderWidth: 1, borderColor: '#e2e8f0', borderRadius: 12, paddingHorizontal: 16, height: 54, fontSize: 16, color: colors.textPrimary },
  inputWithIcon:   { flexDirection: 'row', alignItems: 'center' },
  eyeIcon:         { backgroundColor: colors.surface, borderTopRightRadius: 12, borderBottomRightRadius: 12, borderTopWidth: 1, borderBottomWidth: 1, borderRightWidth: 1, borderColor: '#e2e8f0', height: 54, width: 50, justifyContent: 'center', alignItems: 'center' },
  strengthWrapper: { height: 6, backgroundColor: '#e2e8f0', borderRadius: 3, overflow: 'hidden' },
  strengthBar:     { height: '100%', transition: 'width 0.3s' },
  reqContainer:    { marginTop: 8, flexDirection: 'row', flexWrap: 'wrap', gap: 12 },
  reqRow:          { flexDirection: 'row', alignItems: 'center', gap: 4 },
  reqText:         { fontSize: 11, color: '#94a3b8' },
  primaryButton:   { backgroundColor: colors.accent, borderRadius: 12, height: 54, justifyContent: 'center', alignItems: 'center', marginTop: 12, marginBottom: 24, shadowColor: colors.accent, shadowOffset: { width: 0, height: 4 }, shadowOpacity: 0.2, shadowRadius: 8, elevation: 4 },
  disabled:        { opacity: 0.5 },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: '700' },
  footerContainer: { flexDirection: 'row', justifyContent: 'center', alignItems: 'center', marginBottom: 16 },
  footerText:      { color: colors.textSecondary, fontSize: 14 },
  secondaryActionText: { color: colors.accent, fontSize: 14, fontWeight: '700' },
});
