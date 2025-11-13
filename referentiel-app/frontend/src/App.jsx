import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import PrivateRoute from './components/PrivateRoute';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import Clients from './pages/Clients';
import './App.css';

function App() {
  return (
    <AuthProvider>
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route
            path="/"
            element={
              <PrivateRoute>
                <Dashboard />
              </PrivateRoute>
            }
          />
          <Route
            path="/clients"
            element={
              <PrivateRoute>
                <Clients />
              </PrivateRoute>
            }
          />
          <Route
            path="/produits"
            element={
              <PrivateRoute>
                <div>Page Produits - À implémenter</div>
              </PrivateRoute>
            }
          />
          <Route
            path="/employes"
            element={
              <PrivateRoute>
                <div>Page Employés - À implémenter</div>
              </PrivateRoute>
            }
          />
          <Route
            path="/fournisseurs"
            element={
              <PrivateRoute>
                <div>Page Fournisseurs - À implémenter</div>
              </PrivateRoute>
            }
          />
          <Route path="*" element={<Navigate to="/" />} />
        </Routes>
      </Router>
    </AuthProvider>
  );
}

export default App;
