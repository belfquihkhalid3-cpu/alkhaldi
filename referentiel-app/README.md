# Application de Référentiel - Spring Boot + React

Application complète de gestion de référentiel pour gérer les Clients, Produits, Employés et Fournisseurs.

## Fonctionnalités

- **Authentification JWT** : Système complet de connexion/inscription avec tokens JWT
- **Gestion CRUD** : Créer, Lire, Modifier, Supprimer pour toutes les entités
- **Recherche et Filtrage** : Recherche en temps réel sur toutes les données
- **Import/Export** : Exporter les données en Excel et CSV
- **Interface Moderne** : Interface utilisateur responsive avec React
- **API RESTful** : Backend Spring Boot avec API REST complète

## Technologies Utilisées

### Backend
- **Spring Boot 3.2.0**
- **Spring Security** avec JWT
- **Spring Data JPA**
- **PostgreSQL**
- **Apache POI** (Export Excel)
- **OpenCSV** (Export CSV)
- **Lombok**

### Frontend
- **React 18** avec Vite
- **React Router** pour la navigation
- **Axios** pour les appels API
- **CSS moderne** avec design responsive

## Structure du Projet

```
referentiel-app/
├── backend/                 # Application Spring Boot
│   ├── src/
│   │   ├── main/
│   │   │   ├── java/com/referentiel/
│   │   │   │   ├── entity/          # Entités JPA
│   │   │   │   ├── repository/      # Repositories
│   │   │   │   ├── service/         # Services métier
│   │   │   │   ├── controller/      # Controllers REST
│   │   │   │   ├── security/        # Configuration sécurité
│   │   │   │   └── dto/             # DTOs
│   │   │   └── resources/
│   │   │       └── application.properties
│   │   └── test/
│   └── pom.xml
│
└── frontend/                # Application React
    ├── src/
    │   ├── components/      # Composants réutilisables
    │   ├── pages/          # Pages de l'application
    │   ├── services/       # Services API
    │   ├── context/        # Context React
    │   └── App.jsx
    └── package.json
```

## Prérequis

- **Java 17** ou supérieur
- **Maven 3.6+**
- **Node.js 18+** et npm
- **PostgreSQL 12+**

## Installation

### 1. Configuration de la Base de Données

Créez une base de données PostgreSQL :

```sql
CREATE DATABASE referentiel_db;
```

### 2. Configuration du Backend

1. Accédez au dossier backend :
```bash
cd backend
```

2. Modifiez `src/main/resources/application.properties` avec vos informations PostgreSQL :
```properties
spring.datasource.url=jdbc:postgresql://localhost:5432/referentiel_db
spring.datasource.username=votre_utilisateur
spring.datasource.password=votre_mot_de_passe
```

3. Compilez et lancez l'application :
```bash
mvn clean install
mvn spring-boot:run
```

Le backend sera accessible sur `http://localhost:8080`

### 3. Configuration du Frontend

1. Accédez au dossier frontend :
```bash
cd frontend
```

2. Installez les dépendances :
```bash
npm install
```

3. Lancez l'application en mode développement :
```bash
npm run dev
```

Le frontend sera accessible sur `http://localhost:5173`

## Utilisation

### 1. Inscription et Connexion

1. Accédez à `http://localhost:5173/register` pour créer un compte
2. Connectez-vous avec vos identifiants à `http://localhost:5173/login`

### 2. Navigation

Une fois connecté, vous pouvez :
- Voir le tableau de bord avec les statistiques
- Gérer les Clients
- Gérer les Produits
- Gérer les Employés
- Gérer les Fournisseurs

### 3. Fonctionnalités CRUD

Pour chaque entité, vous pouvez :
- **Créer** : Cliquez sur "Nouveau [Entité]"
- **Lire** : Voir la liste complète dans le tableau
- **Modifier** : Cliquez sur "Modifier" pour une ligne
- **Supprimer** : Cliquez sur "Supprimer" (avec confirmation)

### 4. Recherche

Utilisez la barre de recherche en haut pour filtrer les résultats en temps réel.

### 5. Export

Cliquez sur "Export Excel" ou "Export CSV" pour télécharger les données.

## API Endpoints

### Authentification
- `POST /api/auth/register` - Inscription
- `POST /api/auth/login` - Connexion
- `GET /api/auth/me` - Profil utilisateur

### Clients
- `GET /api/clients` - Liste des clients
- `GET /api/clients/{id}` - Détails d'un client
- `POST /api/clients` - Créer un client
- `PUT /api/clients/{id}` - Modifier un client
- `DELETE /api/clients/{id}` - Supprimer un client
- `GET /api/clients/search?keyword=` - Rechercher

### Produits
- `GET /api/produits` - Liste des produits
- `GET /api/produits/{id}` - Détails d'un produit
- `POST /api/produits` - Créer un produit
- `PUT /api/produits/{id}` - Modifier un produit
- `DELETE /api/produits/{id}` - Supprimer un produit
- `GET /api/produits/search?keyword=` - Rechercher

### Employés
- `GET /api/employes` - Liste des employés
- `GET /api/employes/{id}` - Détails d'un employé
- `POST /api/employes` - Créer un employé
- `PUT /api/employes/{id}` - Modifier un employé
- `DELETE /api/employes/{id}` - Supprimer un employé
- `GET /api/employes/search?keyword=` - Rechercher

### Fournisseurs
- `GET /api/fournisseurs` - Liste des fournisseurs
- `GET /api/fournisseurs/{id}` - Détails d'un fournisseur
- `POST /api/fournisseurs` - Créer un fournisseur
- `PUT /api/fournisseurs/{id}` - Modifier un fournisseur
- `DELETE /api/fournisseurs/{id}` - Supprimer un fournisseur
- `GET /api/fournisseurs/search?keyword=` - Rechercher

### Export
- `GET /api/export/clients/excel` - Export clients Excel
- `GET /api/export/clients/csv` - Export clients CSV
- `GET /api/export/produits/excel` - Export produits Excel
- `GET /api/export/employes/excel` - Export employés Excel
- `GET /api/export/fournisseurs/excel` - Export fournisseurs Excel

## Sécurité

- Toutes les routes API (sauf `/api/auth/**`) nécessitent un token JWT
- Les mots de passe sont hashés avec BCrypt
- CORS activé pour le développement local

## Configuration de Production

### Backend

1. Modifiez `application.properties` :
```properties
# Désactiver la création automatique de schéma
spring.jpa.hibernate.ddl-auto=validate

# Désactiver les logs SQL
spring.jpa.show-sql=false

# Configuration JWT sécurisée
jwt.secret=VOTRE_CLE_SECRETE_LONGUE_ET_SECURISEE
```

2. Construisez le JAR :
```bash
mvn clean package -DskipTests
```

3. Lancez l'application :
```bash
java -jar target/referentiel-backend-1.0.0.jar
```

### Frontend

1. Construisez pour la production :
```bash
npm run build
```

2. Les fichiers statiques seront dans le dossier `dist/`

## Problèmes Courants

### Le backend ne démarre pas
- Vérifiez que PostgreSQL est en cours d'exécution
- Vérifiez les identifiants dans `application.properties`
- Assurez-vous que le port 8080 n'est pas déjà utilisé

### Le frontend ne se connecte pas au backend
- Vérifiez que le backend est en cours d'exécution
- Vérifiez l'URL de l'API dans `frontend/src/services/api.js`
- Vérifiez la configuration CORS dans le backend

### Erreur 401 Unauthorized
- Le token JWT a peut-être expiré, reconnectez-vous
- Vérifiez que le token est bien stocké dans localStorage

## Développement Futur

- [ ] Implémenter les pages CRUD pour Produits, Employés et Fournisseurs
- [ ] Ajouter des graphiques et statistiques avancées
- [ ] Implémenter l'import de données (Excel/CSV)
- [ ] Ajouter la gestion des rôles et permissions
- [ ] Implémenter la pagination côté serveur
- [ ] Ajouter des tests unitaires et d'intégration
- [ ] Dockeriser l'application
- [ ] Ajouter une documentation Swagger/OpenAPI

## Licence

Ce projet est sous licence MIT.

## Auteur

Développé avec Spring Boot et React
