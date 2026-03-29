import React, { useState, useMemo } from 'react';
import { 
  View, 
  Text, 
  TextInput, 
  TouchableOpacity, 
  StyleSheet, 
  KeyboardAvoidingView, 
  Platform, 
  Image, 
  Alert,
  ActivityIndicator,
  ScrollView
} from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Ionicons } from '@expo/vector-icons';
import { colors } from '../theme/colors';
import { apiClient } from '../data/api/apiClient';

export default function RecuperarScreen({ navigation }) {
  const [email, setEmail] = useState('');
  const [code, setCode] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showPass, setShowPass] = useState(false);
  const [showConf, setShowConf] = useState(false);
  const [step, setStep] = useState(1); // 1: Email, 2: Code & New Password
  const [loading, setLoading] = useState(false);

  // Strength Logic
  const strength = useMemo(() => {
    let score = 0;
    if (newPassword.length >= 8) score++;
    if (/[A-Z]/.test(newPassword)) score++;
    if (/\d/.test(newPassword)) score++;
    return score;
  }, [newPassword]);

  const handleRequestCode = async () => {
    if (!email.trim() || !email.includes('@')) {
      Alert.alert("Error", "Por favor, ingresa un correo electrónico válido.");
      return;
    }

    setLoading(true);
    try {
      await apiClient('/auth/forgot-password', {
        method: 'POST',
        body: JSON.stringify({ email })
      });

      Alert.alert(
        "Código Enviado",
        "Si el correo está registrado, recibirás un código de 6 dígitos en breve.",
        [{ text: "OK", onPress: () => setStep(2) }]
      );
    } catch (e) {
      Alert.alert("Error", "No se pudo enviar el código. Revisa tu conexión.");
    } finally {
      setLoading(false);
    }
  };

  const handleResetPassword = async () => {
    if (code.length !== 6) {
      Alert.alert("Error", "El código debe tener 6 dígitos.");
      return;
    }
    
    // Validar requerimientos
    if (newPassword.length < 8) {
      Alert.alert('Seguridad', 'La contraseña debe tener al menos 8 caracteres.');
      return;
    }
    if (!/[A-Z]/.test(newPassword)) {
      Alert.alert('Seguridad', 'La contraseña debe incluir al menos una mayúscula.');
      return;
    }
    if (!/\d/.test(newPassword)) {
      Alert.alert('Seguridad', 'La contraseña debe incluir al menos un número.');
      return;
    }

    if (newPassword !== confirmPassword) {
      Alert.alert("Error", "Las contraseñas no coinciden.");
      return;
    }

    setLoading(true);
    try {
      await apiClient('/auth/reset-password', {
        method: 'POST',
        body: JSON.stringify({ 
          email, 
          codigo: code, 
          nueva_password: newPassword 
        })
      });

      Alert.alert(
        "Éxito",
        "Tu contraseña ha sido restablecida. Ya puedes iniciar sesión.",
        [{ text: "Entendido", onPress: () => navigation.goBack() }]
      );
    } catch (e) {
      Alert.alert("Error", "Código inválido, expirado o error de servidor.");
    } finally {
      setLoading(false);
    }
  };

  const renderRequirement = (text, met) => (
    <View style={styles.reqRow}>
      <Ionicons name={met ? "checkmark-circle" : "ellipse-outline"} size={12} color={met ? "#10b981" : "#94a3b8"} />
      <Text style={[styles.reqText, met && { color: '#10b981' }]}>{text}</Text>
    </View>
  );

  return (
    <SafeAreaView style={styles.safeArea}>
      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
        <ScrollView contentContainerStyle={{ flexGrow: 1, justifyContent: 'center' }} showsVerticalScrollIndicator={false}>
          <View style={styles.formContainer}>
            
            <View style={styles.headerContainer}>
              <Image source={require('../../assets/logo.png')} style={styles.logo} resizeMode="contain" />
              <Text style={styles.title}>{step === 1 ? 'Recuperar' : 'Restablecer'}</Text>
              <Text style={styles.subtitle}>PASO {step} DE 2</Text>
            </View>

            <View style={styles.instructionsContainer}>
              <Text style={styles.instructionsText}>
                {step === 1 
                  ? "Ingresa tu correo para recibir un código de seguridad."
                  : "Ingresa el código de 6 dígitos enviado a tu correo y tu nueva contraseña."}
              </Text>
            </View>

            {step === 1 ? (
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
            ) : (
              <View>
                <View style={styles.inputGroup}>
                  <Text style={styles.label}>Código de 6 dígitos</Text>
                  <TextInput
                    style={[styles.input, { letterSpacing: 8, textAlign: 'center', fontWeight: 'bold' }]}
                    placeholder="000000"
                    maxLength={6}
                    keyboardType="numeric"
                    value={code}
                    onChangeText={setCode}
                  />
                </View>
                
                <View style={styles.inputGroup}>
                  <Text style={styles.label}>Nueva Contraseña</Text>
                  <View style={styles.inputWithIcon}>
                    <TextInput
                      style={[styles.input, { flex: 1, borderTopRightRadius: 0, borderBottomRightRadius: 0, borderRightWidth: 0 }]}
                      placeholder="••••••••"
                      secureTextEntry={!showPass}
                      value={newPassword}
                      onChangeText={setNewPassword}
                    />
                    <TouchableOpacity style={styles.eyeIcon} onPress={() => setShowPass(!showPass)}>
                      <Ionicons name={showPass ? "eye-off" : "eye"} size={22} color="#94a3b8" />
                    </TouchableOpacity>
                  </View>
                  
                  {newPassword.length > 0 && (
                    <View style={{ marginTop: 8 }}>
                      <View style={styles.strengthWrapper}>
                        <View style={[styles.strengthBar, { width: `${(strength / 3) * 100}%`, backgroundColor: strength === 3 ? '#10b981' : strength === 2 ? '#f59e0b' : '#ef4444' }]} />
                      </View>
                      <View style={styles.reqContainer}>
                        {renderRequirement('8+ car.', newPassword.length >= 8)}
                        {renderRequirement('Mayús.', /[A-Z]/.test(newPassword))}
                        {renderRequirement('Un número', /\d/.test(newPassword))}
                      </View>
                    </View>
                  )}
                </View>

                <View style={styles.inputGroup}>
                  <Text style={styles.label}>Confirmar Contraseña</Text>
                  <View style={styles.inputWithIcon}>
                    <TextInput
                      style={[styles.input, { flex: 1, borderTopRightRadius: 0, borderBottomRightRadius: 0, borderRightWidth: 0 }]}
                      placeholder="••••••••"
                      secureTextEntry={!showConf}
                      value={confirmPassword}
                      onChangeText={setConfirmPassword}
                    />
                    <TouchableOpacity style={styles.eyeIcon} onPress={() => setShowConf(!showConf)}>
                      <Ionicons name={showConf ? "eye-off" : "eye"} size={22} color="#94a3b8" />
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            )}

            <TouchableOpacity 
              style={[styles.primaryButton, (loading || (step === 2 && newPassword.length > 0 && strength < 3)) && styles.disabled]}
              onPress={step === 1 ? handleRequestCode : handleResetPassword}
              disabled={loading || (step === 2 && newPassword.length > 0 && strength < 3)}
            >
              {loading ? (
                <ActivityIndicator color="#fff" />
              ) : (
                <Text style={styles.primaryButtonText}>
                  {step === 1 ? 'Enviar código' : 'Cambiar Contraseña'}
                </Text>
              )}
            </TouchableOpacity>

            <TouchableOpacity 
              style={styles.secondaryButton}
              onPress={() => step === 2 ? setStep(1) : navigation.goBack()}
            >
              <Text style={styles.secondaryButtonText}>
                {step === 2 ? 'Regresar al paso 1' : 'Cancelar'}
              </Text>
            </TouchableOpacity>

          </View>
        </ScrollView>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  safeArea: { flex: 1, backgroundColor: colors.background },
  container: { flex: 1 },
  formContainer: { paddingHorizontal: 32, width: '100%', maxWidth: 400, alignSelf: 'center', paddingVertical: 24 },
  headerContainer: { alignItems: 'center', marginBottom: 24 },
  logo: { width: 120, height: 120, marginBottom: 16 },
  title: { fontSize: 28, fontWeight: '800', color: colors.primary, letterSpacing: 0.5 },
  subtitle: { fontSize: 12, fontWeight: '700', color: colors.textSecondary, marginTop: 4, letterSpacing: 2 },
  instructionsContainer: { marginBottom: 24, backgroundColor: '#eff6ff', padding: 16, borderRadius: 12, borderWidth: 1, borderColor: '#dbeafe' },
  instructionsText: { fontSize: 14, color: colors.accent, textAlign: 'center', lineHeight: 20, fontWeight: '500' },
  inputGroup: { marginBottom: 16 },
  label: { fontSize: 13, fontWeight: '700', color: colors.textPrimary, marginBottom: 8, textTransform: 'uppercase', letterSpacing: 0.5 },
  input: { backgroundColor: colors.surface, borderWidth: 1, borderColor: '#e2e8f0', borderRadius: 12, paddingHorizontal: 16, height: 52, fontSize: 16, color: colors.textPrimary },
  inputWithIcon: { flexDirection: 'row', alignItems: 'center' },
  eyeIcon: { backgroundColor: colors.surface, borderTopRightRadius: 12, borderBottomRightRadius: 12, borderTopWidth: 1, borderBottomWidth: 1, borderRightWidth: 1, borderColor: '#e2e8f0', height: 52, width: 50, justifyContent: 'center', alignItems: 'center' },
  strengthWrapper: { height: 6, backgroundColor: '#e2e8f0', borderRadius: 3, overflow: 'hidden' },
  strengthBar: { height: '100%' },
  reqContainer: { marginTop: 8, flexDirection: 'row', flexWrap: 'wrap', gap: 10 },
  reqRow: { flexDirection: 'row', alignItems: 'center', gap: 4 },
  reqText: { fontSize: 11, color: '#94a3b8' },
  primaryButton: { backgroundColor: colors.accent, borderRadius: 12, height: 52, justifyContent: 'center', alignItems: 'center', marginBottom: 16, elevation: 4, marginTop: 12 },
  disabled: { opacity: 0.5 },
  primaryButtonText: { color: colors.surface, fontSize: 16, fontWeight: '700' },
  secondaryButton: { height: 52, justifyContent: 'center', alignItems: 'center' },
  secondaryButtonText: { color: colors.textSecondary, fontSize: 14, fontWeight: '600' }
});