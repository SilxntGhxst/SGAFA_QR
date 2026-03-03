import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { NavigationContainer } from '@react-navigation/native';

import LoginScreen from '../screens/LoginScreen';
import RegisterScreen from '../screens/RegisterScreen';
import RecuperarScreen from '../screens/RecuperarScreen';
import TabNavigator from './TabNavigator';
import EscanerScreen from '../screens/EscanerScreen';
import IncidenciaScreen from '../screens/IncidenciaScreen';

const Stack = createNativeStackNavigator();

export default function AppNavigator() {
  return (
    <NavigationContainer>
      <Stack.Navigator screenOptions={{ headerShown: false }} initialRouteName="Login">
        <Stack.Screen name="Login" component={LoginScreen} />
        <Stack.Screen name="Register" component={RegisterScreen} />
        <Stack.Screen name="Recuperar" component={RecuperarScreen} />
        <Stack.Screen name="Main" component={TabNavigator} />
        <Stack.Screen name="Escaner" component={EscanerScreen} />
        <Stack.Screen name="Incidencia" component={IncidenciaScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}