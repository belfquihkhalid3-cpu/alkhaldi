import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Navbar from '../components/Navbar';
import { clientAPI, produitAPI, employeAPI, fournisseurAPI } from '../services/api';
import './Crud.css';

const Dashboard = () => {
  const [stats, setStats] = useState({
    clients: 0,
    produits: 0,
    employes: 0,
    fournisseurs: 0,
  });

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      const [clientsRes, produitsRes, employesRes, fournisseursRes] = await Promise.all([
        clientAPI.getAll(),
        produitAPI.getAll(),
        employeAPI.getAll(),
        fournisseurAPI.getAll(),
      ]);

      setStats({
        clients: clientsRes.data.length,
        produits: produitsRes.data.length,
        employes: employesRes.data.length,
        fournisseurs: fournisseursRes.data.length,
      });
    } catch (error) {
      console.error('Erreur lors du chargement des statistiques:', error);
    }
  };

  return (
    <>
      <Navbar />
      <div className="container">
        <h1>Tableau de Bord</h1>
        <div className="dashboard">
          <Link to="/clients" style={{ textDecoration: 'none' }}>
            <div className="card">
              <h3>Clients</h3>
              <p>{stats.clients}</p>
            </div>
          </Link>
          <Link to="/produits" style={{ textDecoration: 'none' }}>
            <div className="card">
              <h3>Produits</h3>
              <p>{stats.produits}</p>
            </div>
          </Link>
          <Link to="/employes" style={{ textDecoration: 'none' }}>
            <div className="card">
              <h3>Employés</h3>
              <p>{stats.employes}</p>
            </div>
          </Link>
          <Link to="/fournisseurs" style={{ textDecoration: 'none' }}>
            <div className="card">
              <h3>Fournisseurs</h3>
              <p>{stats.fournisseurs}</p>
            </div>
          </Link>
        </div>
      </div>
    </>
  );
};

export default Dashboard;
