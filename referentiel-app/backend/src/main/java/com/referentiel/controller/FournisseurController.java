package com.referentiel.controller;

import com.referentiel.entity.Fournisseur;
import com.referentiel.service.FournisseurService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/fournisseurs")
@CrossOrigin(origins = "*")
@RequiredArgsConstructor
public class FournisseurController {

    private final FournisseurService fournisseurService;

    @GetMapping
    public ResponseEntity<List<Fournisseur>> getAllFournisseurs() {
        return ResponseEntity.ok(fournisseurService.getAllFournisseurs());
    }

    @GetMapping("/{id}")
    public ResponseEntity<Fournisseur> getFournisseurById(@PathVariable Long id) {
        return fournisseurService.getFournisseurById(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    @PostMapping
    public ResponseEntity<Fournisseur> createFournisseur(@Valid @RequestBody Fournisseur fournisseur) {
        Fournisseur createdFournisseur = fournisseurService.createFournisseur(fournisseur);
        return ResponseEntity.status(HttpStatus.CREATED).body(createdFournisseur);
    }

    @PutMapping("/{id}")
    public ResponseEntity<Fournisseur> updateFournisseur(
            @PathVariable Long id,
            @Valid @RequestBody Fournisseur fournisseur) {
        try {
            Fournisseur updatedFournisseur = fournisseurService.updateFournisseur(id, fournisseur);
            return ResponseEntity.ok(updatedFournisseur);
        } catch (RuntimeException e) {
            return ResponseEntity.notFound().build();
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteFournisseur(@PathVariable Long id) {
        try {
            fournisseurService.deleteFournisseur(id);
            return ResponseEntity.noContent().build();
        } catch (RuntimeException e) {
            return ResponseEntity.notFound().build();
        }
    }

    @GetMapping("/search")
    public ResponseEntity<List<Fournisseur>> searchFournisseurs(@RequestParam String keyword) {
        return ResponseEntity.ok(fournisseurService.searchFournisseurs(keyword));
    }

    @GetMapping("/actif/{actif}")
    public ResponseEntity<List<Fournisseur>> getFournisseursByActif(@PathVariable Boolean actif) {
        return ResponseEntity.ok(fournisseurService.getFournisseursByActif(actif));
    }

    @GetMapping("/ville/{ville}")
    public ResponseEntity<List<Fournisseur>> getFournisseursByVille(@PathVariable String ville) {
        return ResponseEntity.ok(fournisseurService.getFournisseursByVille(ville));
    }
}
