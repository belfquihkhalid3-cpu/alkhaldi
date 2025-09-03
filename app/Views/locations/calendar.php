<!-- ========================================
     FICHIER : Views/locations/calendar.php
     Vue calendrier - Planning des locations
     ======================================== -->

<div class="page-content">
    <div class="container-fluid">
        
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar text-primary"></i> Planning des Locations
            </h1>
            <div>
                <a href="<?= site_url('locations') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-list"></i> Vue liste
                </a>
                <a href="<?= site_url('locations/add') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvelle location
                </a>
            </div>
        </div>

        <!-- Légende des statuts -->
        <div class="card shadow mb-4">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <small class="text-muted">Légende des statuts :</small>
                        <span class="badge" style="background-color: #ffc107; color: #000;">En attente</span>
                        <span class="badge" style="background-color: #28a745; color: #fff;">Confirmée</span>
                        <span class="badge" style="background-color: #007bff; color: #fff;">En cours</span>
                        <span class="badge" style="background-color: #6c757d; color: #fff;">Terminée</span>
                        <span class="badge" style="background-color: #dc3545; color: #fff;">Annulée</span>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-outline-primary btn-sm" id="prevMonth">
                            <i class="fas fa-chevron-left"></i> Précédent
                        </button>
                        <button class="btn btn-outline-primary btn-sm" id="today">Aujourd'hui</button>
                        <button class="btn btn-outline-primary btn-sm" id="nextMonth">
                            <i class="fas fa-chevron-right"></i> Suivant
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendrier -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        <!-- Modal pour voir/ajouter une location -->
        <div class="modal fade" id="locationModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="locationModalTitle">Détails de la location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="locationModalBody">
                        <!-- Contenu dynamique -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal pour créer une nouvelle location -->
        <div class="modal fade" id="newLocationModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nouvelle Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Période sélectionnée : <span id="selectedDateRange"></span></p>
                        <div class="text-center">
                            <a href="#" id="createLocationBtn" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Créer une location pour cette période
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inclure FullCalendar CSS et JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/locales/fr.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: '',
            center: 'title',
            right: ''
        },
        height: 'auto',
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5, 6], // Lundi - Samedi
            startTime: '06:00',
            endTime: '22:00',
        },
        
        // Charger les événements
        events: {
            url: '<?= site_url('locations/calendar_api') ?>',
            method: 'GET',
            failure: function() {
                alert('Erreur lors du chargement des locations');
            }
        },
        
        // Sélection d'une période pour créer une location
        select: function(info) {
            const start = info.start;
            const end = info.end;
            
            // Ajuster la date de fin pour l'affichage
            const endDisplay = new Date(end);
            endDisplay.setDate(endDisplay.getDate() - 1);
            
            const dateRange = formatDate(start) + 
                (start.toDateString() !== endDisplay.toDateString() ? 
                 ' au ' + formatDate(endDisplay) : '');
            
            document.getElementById('selectedDateRange').textContent = dateRange;
            
            // Construire l'URL avec les dates
            const createUrl = '<?= site_url('locations/add') ?>?' + 
                'date_debut=' + encodeURIComponent(formatDateTime(start)) +
                '&date_fin=' + encodeURIComponent(formatDateTime(endDisplay));
            
            document.getElementById('createLocationBtn').href = createUrl;
            
            // Afficher le modal
            new bootstrap.Modal(document.getElementById('newLocationModal')).show();
            
            calendar.unselect();
        },
        
        // Clic sur un événement existant
        eventClick: function(info) {
            const event = info.event;
            const props = event.extendedProps;
            
            document.getElementById('locationModalTitle').textContent = event.title;
            
            const modalBody = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-user"></i> Client</h6>
                        <p>${props.client}</p>
                        
                        <h6><i class="fas fa-car"></i> Véhicule</h6>
                        <p>${props.vehicle || '<span class="text-muted">Non assigné</span>'}</p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock"></i> Période</h6>
                        <p>${formatDateTime(event.start)} au ${formatDateTime(event.end)}</p>
                        
                        <h6><i class="fas fa-info-circle"></i> Statut</h6>
                        <p><span class="badge" style="background-color: ${event.backgroundColor}">${getStatusText(props.status)}</span></p>
                        
                        <h6><i class="fas fa-tag"></i> Type</h6>
                        <p>${getTypeText(props.type)}</p>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <a href="<?= site_url('locations/view/') ?>${event.id}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> Voir détails
                    </a>
                    <a href="<?= site_url('locations/edit/') ?>${event.id}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                </div>
            `;
            
            document.getElementById('locationModalBody').innerHTML = modalBody;
            new bootstrap.Modal(document.getElementById('locationModal')).show();
        },
        
        // Personnalisation de l'affichage des événements
        eventDisplay: 'block',
        eventTextColor: '#fff',
        eventBorderColor: 'transparent',
        
        // Responsive
        windowResize: function(view) {
            if (window.innerWidth < 768) {
                calendar.changeView('listWeek');
            } else {
                calendar.changeView('dayGridMonth');
            }
        }
    });
    
    calendar.render();
    
    // Boutons de navigation personnalisés
    document.getElementById('prevMonth').addEventListener('click', function() {
        calendar.prev();
    });
    
    document.getElementById('nextMonth').addEventListener('click', function() {
        calendar.next();
    });
    
    document.getElementById('today').addEventListener('click', function() {
        calendar.today();
    });
    
    // Vue responsive au chargement
    if (window.innerWidth < 768) {
        calendar.changeView('listWeek');
    }
    
    // Fonctions utilitaires
    function formatDate(date) {
        return date.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    function formatDateTime(date) {
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }) + ' ' + date.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    function getStatusText(status) {
        const statuses = {
            'en_attente': 'En attente',
            'confirmee': 'Confirmée',
            'en_cours': 'En cours',
            'terminee': 'Terminée',
            'annulee': 'Annulée'
        };
        return statuses[status] || status;
    }
    
    function getTypeText(type) {
        const types = {
            'transfert': 'Transfert',
            'location_journee': 'Location journée',
            'location_longue': 'Location longue durée',
            'evenement': 'Événement'
        };
        return types[type] || type;
    }
});

// Actualisation automatique du calendrier toutes les 2 minutes
setInterval(function() {
    const calendar = FullCalendar.getCalendar(document.getElementById('calendar'));
    if (calendar) {
        calendar.refetchEvents();
    }
}, 120000);
</script>

<style>
/* Personnalisation du calendrier */
.fc-theme-standard .fc-scrollgrid {
    border: 1px solid #e3e6f0;
}

.fc-theme-standard td, .fc-theme-standard th {
    border-color: #e3e6f0;
}

.fc-daygrid-day-number {
    color: #5a5c69;
    font-weight: 600;
}

.fc-daygrid-day.fc-day-today {
    background-color: rgba(78, 115, 223, 0.1);
}

.fc-event {
    border-radius: 3px;
    font-size: 0.85rem;
    padding: 2px 5px;
    margin: 1px 0;
}

.fc-event:hover {
    opacity: 0.8;
    cursor: pointer;
}

.fc-button-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.fc-button-primary:hover {
    background-color: #2e59d9;
    border-color: #2e59d9;
}

.fc-toolbar-title {
    color: #5a5c69;
    font-weight: 600;
}

/* Modal personnalisations */
.modal-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.badge {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .fc-toolbar {
        flex-direction: column;
    }
    
    .fc-toolbar-chunk {
        margin: 0.25rem 0;
    }
    
    .fc-event {
        font-size: 0.75rem;
        padding: 1px 3px;
    }
}

/* Animation pour les événements */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fc-event {
    animation: fadeIn 0.3s ease-in-out;
}
</style>