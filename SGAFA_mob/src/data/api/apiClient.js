import AsyncStorage from '@react-native-async-storage/async-storage';

export const API_BASE_URL = `http://${process.env.EXPO_PUBLIC_API_IP}:8080/api`;
const API_KEY = process.env.EXPO_PUBLIC_API_KEY || 'sgafa_qr_api_key_2024_secure';

/**
 * Cliente HTTP base.
 * Adjunta automáticamente el API Key y el Bearer token si existe en AsyncStorage.
 */
export const apiClient = async (endpoint, options = {}) => {
  const url = `${API_BASE_URL}${endpoint}`;

  // Leer token almacenado
  let token = null;
  try {
    token = await AsyncStorage.getItem('auth_token');
  } catch (_) {}

  const config = {
    ...options,
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      'X-API-Key': API_KEY,
      // Inyectar Bearer token si existe
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...options.headers,
    },
  };

  const response = await fetch(url, config);
  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    throw new Error(data.message || data.detail || `Error en el servidor (${response.status})`);
  }

  return data;
};
