import React from 'react';
import { ActivityIndicator, View } from 'react-native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { NavigationContainer } from '@react-navigation/native';

import { AuthProvider, useAuth } from '../domain/AuthContext';

import LoginScreen    from '../screens/LoginScreen';
import RegisterScreen from '../screens/RegisterScreen';
import RecuperarScreen from '../screens/RecuperarScreen';
import TabNavigator   from './TabNavigator';
import EscanerScreen  from '../screens/EscanerScreen';
import IncidenciaScreen    from '../screens/IncidenciaScreen';
import SincronizacionScreen from '../screens/SincronizacionScreen';

const Stack = createNativeStackNavigator();

/** Stacks públicos (sin sesión) */
function AuthStack() {
  return (
    <Stack.Navigator screenOptions={{ headerShown: false }} initialRouteName="Login">
      <Stack.Screen name="Login"    component={LoginScreen} />
      <Stack.Screen name="Register" component={RegisterScreen} />
      <Stack.Screen name="Recuperar" component={RecuperarScreen} />
    </Stack.Navigator>
  );
}

/** Stacks privados (con sesión activa) */
function AppStack() {
  return (
    <Stack.Navigator screenOptions={{ headerShown: false }} initialRouteName="Main">
      <Stack.Screen name="Main"          component={TabNavigator} />
      <Stack.Screen name="Escaner"       component={EscanerScreen} />
      <Stack.Screen name="Incidencia"    component={IncidenciaScreen} />
      <Stack.Screen name="Sincronizacion" component={SincronizacionScreen} />
    </Stack.Navigator>
  );
}

/** Guard: muestra splash mientras carga, luego rootea según sesión */
function RootNavigator() {
  const { user, isLoading } = useAuth();

  if (isLoading) {
    return (
      <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#0f172a' }}>
        <ActivityIndicator size="large" color="#6366f1" />
      </View>
    );
  }

  return user ? <AppStack /> : <AuthStack />;
}

export default function AppNavigator() {
  return (
    <AuthProvider>
      <NavigationContainer>
        <RootNavigator />
      </NavigationContainer>
    </AuthProvider>
  );
}