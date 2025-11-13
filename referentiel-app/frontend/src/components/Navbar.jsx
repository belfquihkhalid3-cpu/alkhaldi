import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './Navbar.css';

const Navbar = () => {
  const { user, logout } = useAuth();

  return (
    <nav className="navbar">
      <div className="navbar-container">
        <Link to="/" className="navbar-logo">
          Référentiel App
        </Link>
        <ul className="navbar-menu">
          <li><Link to="/clients">Clients</Link></li>
          <li><Link to="/produits">Produits</Link></li>
          <li><Link to="/employes">Employés</Link></li>
          <li><Link to="/fournisseurs">Fournisseurs</Link></li>
        </ul>
        <div className="navbar-user">
          <span>Bonjour, {user?.username}</span>
          <button onClick={logout} className="btn-logout">
            Déconnexion
          </button>
        </div>
      </div>
    </nav>
  );
};

export default Navbar;
