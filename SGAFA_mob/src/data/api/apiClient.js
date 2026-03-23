export const API_BASE_URL = `http://${process.env.EXPO_PUBLIC_API_IP}:8080/api`;

export const apiClient = async (endpoint, options = {}) => {
  const url = `${API_BASE_URL}${endpoint}`;
  
  const config = {
    ...options,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
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
