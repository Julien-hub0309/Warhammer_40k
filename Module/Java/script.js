// Fichier: script.js

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------
    // 1. Logique du Menu de Navigation (pour mobile)
    // Simule un Auspex de commande tactique
    // -------------------------------------------------------------------
    const menuToggle = document.querySelector('.menu-toggle');
    const navList = document.querySelector('.nav-list');

    if (menuToggle && navList) {
        menuToggle.addEventListener('click', () => {
            // Basculer la classe 'active' pour afficher/masquer le menu
            navList.classList.toggle('active');

            // Changer l'icône (barres à croix) pour un meilleur feedback utilisateur
            const icon = menuToggle.querySelector('i');
            if (navList.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
                menuToggle.setAttribute('aria-label', 'Fermer le menu de navigation');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
                menuToggle.setAttribute('aria-label', 'Ouvrir le menu de navigation');
            }
        });
    }

    // -------------------------------------------------------------------
    // 2. Fonctionnalité de Recherche de Mot-Clé (Auspex Search)
    // Permet de chercher un terme dans le contenu du Codex
    // -------------------------------------------------------------------

    // On attache la fonction de recherche globalement pour le bouton onclick
    window.searchKeyword = function() {
        const input = document.getElementById('searchInput');
        const searchTerm = input.value.toLowerCase().trim();
        const codexContent = document.querySelector('.glossaire-content');

        if (!searchTerm) {
            // Réinitialiser la mise en évidence si la recherche est vide
            clearHighlights(codexContent);
            alert("Entrez un terme à scanner dans le Codex.");
            return;
        }

        // Réinitialiser avant chaque nouvelle recherche
        clearHighlights(codexContent);

        // Sélectionner tout le texte brut dans le Codex pour la recherche
        const textToSearch = codexContent.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        let matchCount = 0;

        // On utilise innerHTML pour remplacer le texte trouvé par un span de mise en évidence
        // Attention: Manipuler innerHTML est moins sécurisé si le contenu venait de l'extérieur. Ici, le contenu est statique.
        codexContent.innerHTML = codexContent.innerHTML.replace(regex, (match) => {
            matchCount++;
            return `<span class="highlight-term">${match}</span>`;
        });

        if (matchCount > 0) {
            alert(`Scan terminé. ${matchCount} occurrence(s) trouvée(s) pour "${searchTerm}" dans le Codex.`);
            // Défilement vers la première occurrence
            const firstHighlight = codexContent.querySelector('.highlight-term');
            if (firstHighlight) {
                 // S'assurer que le détail est ouvert
                document.querySelector('.sacred-codex').open = true;
                firstHighlight.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            alert(`Scan négatif. Terme "${searchTerm}" non trouvé dans le Codex.`);
        }
    };

    function clearHighlights(container) {
        // Enlève tous les spans de mise en évidence
        container.querySelectorAll('.highlight-term').forEach(span => {
            const parent = span.parentNode;
            parent.replaceChild(document.createTextNode(span.textContent), span);
        });
    }


    // -------------------------------------------------------------------
    // 3. Effet Visuel "Glitch Text" (Perturbation des données du Cogitateur)
    // Ajoute un effet visuel instable aux titres (ambiance 40K)
    // -------------------------------------------------------------------
    const glitchTargets = [
        document.querySelector('.site-slogan'),
        document.querySelector('.imperial-edict'),
    ];

    glitchTargets.forEach(target => {
        if (target) {
            // Créer les couches de 'glitch'
            const originalText = target.textContent;
            target.classList.add('glitch-effect');
            target.innerHTML = `
                <span class="glitch-layer layer-1" data-text="${originalText}">${originalText}</span>
                <span class="glitch-layer layer-2" data-text="${originalText}">${originalText}</span>
                <span class="glitch-layer layer-3" data-text="${originalText}">${originalText}</span>
            `;
        }
    });

    // Remarque: L'effet visuel réel du glitch doit être défini en CSS
});