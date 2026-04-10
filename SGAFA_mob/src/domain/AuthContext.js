import React, { createContext, useContext, useState, useEffect } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { loginApi, registerApi, getMeApi, logoutApi } from '../data/api/authApi';
import { apiClient } from '../data/api/apiClient';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser]       = useState(null);
  const [token, setToken]     = useState(null);
  const [isLoading, setIsLoading] = useState(true);  // true mientras verifica sesión guardada

  // ── Inicialización: recuperar sesión de AsyncStorage ─────────────────────
  useEffect(() => {
    const restoreSession = async () => {
      try {
        const storedToken = await AsyncStorage.getItem('auth_token');
        const storedUser  = await AsyncStorage.getItem('auth_user');
        if (storedToken && storedUser) {
          setToken(storedToken);
          // Omitimos setUser para que siempre inicie en el Login según petición del usuario
          // setUser(JSON.parse(storedUser)); 
        }
      } catch (e) {
        console.warn('Error al restaurar sesión:', e);
      } finally {
        setIsLoading(false);
      }
    };
    restoreSession();
  }, []);

  // ── LOGIN ─────────────────────────────────────────────────────────────────
  const login = async (email, password) => {
    const data = await loginApi(email, password);
    // data = { access_token, token_type, user }
    
    // Restricción de roles para la App Móvil: Solo Resguardante (2)
    if (data.user.rol_id !== 2) {
      throw new Error('Esta aplicación es exclusiva para resguardantes. Por favor, usa la plataforma web para administradores o auditores.');
    }

    await AsyncStorage.setItem('auth_token', data.access_token);
    await AsyncStorage.setItem('auth_user',  JSON.stringify(data.user));
    setToken(data.access_token);
    setUser(data.user);
    return data.user;
  };

  // ── REGISTER ──────────────────────────────────────────────────────────────
  const register = async (nombre, apellidos, email, password) => {
    // Registra y luego hace login automático
    await registerApi(nombre, apellidos, email, password);
    return await login(email, password);
  };

  // ── LOGOUT ────────────────────────────────────────────────────────────────
  const logout = async () => {
    try {
      await logoutApi();
    } catch (_) {
      // Si falla la API, igual limpiamos local
    }
    await AsyncStorage.multiRemove(['auth_token', 'auth_user']);
    setToken(null);
    setUser(null);
  };

  // ── UPDATE USER ───────────────────────────────────────────────────────────
  /**
   * Actualiza campos del perfil en la API y sincroniza el estado local.
   * @param {object} campos - Ej: { nombre, apellidos, email } o { password, current_password }
   * @returns {Promise<object>} - Datos del usuario actualizados
   */
  const updateUser = async (campos) => {
    const data = await apiClient(`/usuarios/${user.id}`, {
      method: 'PUT',
      body: JSON.stringify(campos),
    });
    const updatedUser = data.data || data;
    const merged = { ...user, ...updatedUser };
    setUser(merged);
    await AsyncStorage.setItem('auth_user', JSON.stringify(merged));
    return merged;
  };

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, logout, register, updateUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth debe usarse dentro de <AuthProvider>');
  return ctx;
}
