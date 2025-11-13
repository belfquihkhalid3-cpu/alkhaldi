import axios from 'axios';

const API_BASE_URL = 'http://localhost:8080/api';

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add auth token to requests
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  login: (credentials) => api.post('/auth/login', credentials),
  register: (userData) => api.post('/auth/register', userData),
  getCurrentUser: () => api.get('/auth/me'),
};

// Clients API
export const clientAPI = {
  getAll: () => api.get('/clients'),
  getById: (id) => api.get(`/clients/${id}`),
  create: (client) => api.post('/clients', client),
  update: (id, client) => api.put(`/clients/${id}`, client),
  delete: (id) => api.delete(`/clients/${id}`),
  search: (keyword) => api.get(`/clients/search?keyword=${keyword}`),
  exportExcel: () => axios.get(`${API_BASE_URL}/export/clients/excel`, {
    responseType: 'blob',
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }),
  exportCSV: () => axios.get(`${API_BASE_URL}/export/clients/csv`, {
    responseType: 'blob',
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }),
};

// Produits API
export const produitAPI = {
  getAll: () => api.get('/produits'),
  getById: (id) => api.get(`/produits/${id}`),
  create: (produit) => api.post('/produits', produit),
  update: (id, produit) => api.put(`/produits/${id}`, produit),
  delete: (id) => api.delete(`/produits/${id}`),
  search: (keyword) => api.get(`/produits/search?keyword=${keyword}`),
  exportExcel: () => axios.get(`${API_BASE_URL}/export/produits/excel`, {
    responseType: 'blob',
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }),
};

// Employés API
export const employeAPI = {
  getAll: () => api.get('/employes'),
  getById: (id) => api.get(`/employes/${id}`),
  create: (employe) => api.post('/employes', employe),
  update: (id, employe) => api.put(`/employes/${id}`, employe),
  delete: (id) => api.delete(`/employes/${id}`),
  search: (keyword) => api.get(`/employes/search?keyword=${keyword}`),
  exportExcel: () => axios.get(`${API_BASE_URL}/export/employes/excel`, {
    responseType: 'blob',
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }),
};

// Fournisseurs API
export const fournisseurAPI = {
  getAll: () => api.get('/fournisseurs'),
  getById: (id) => api.get(`/fournisseurs/${id}`),
  create: (fournisseur) => api.post('/fournisseurs', fournisseur),
  update: (id, fournisseur) => api.put(`/fournisseurs/${id}`, fournisseur),
  delete: (id) => api.delete(`/fournisseurs/${id}`),
  search: (keyword) => api.get(`/fournisseurs/search?keyword=${keyword}`),
  exportExcel: () => axios.get(`${API_BASE_URL}/export/fournisseurs/excel`, {
    responseType: 'blob',
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }),
};

export default api;
