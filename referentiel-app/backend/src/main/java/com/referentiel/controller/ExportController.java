package com.referentiel.controller;

import com.referentiel.service.*;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.io.IOException;

@RestController
@RequestMapping("/api/export")
@CrossOrigin(origins = "*")
@RequiredArgsConstructor
public class ExportController {

    private final ClientService clientService;
    private final ProduitService produitService;
    private final EmployeService employeService;
    private final FournisseurService fournisseurService;
    private final ExportService exportService;

    @GetMapping("/clients/excel")
    public ResponseEntity<byte[]> exportClientsToExcel() {
        try {
            byte[] excelData = exportService.exportClientsToExcel(clientService.getAllClients());

            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_OCTET_STREAM);
            headers.setContentDispositionFormData("attachment", "clients.xlsx");

            return new ResponseEntity<>(excelData, headers, HttpStatus.OK);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }

    @GetMapping("/clients/csv")
    public ResponseEntity<String> exportClientsToCSV() {
        try {
            String csvData = exportService.exportClientsToCSV(clientService.getAllClients());

            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.parseMediaType("text/csv"));
            headers.setContentDispositionFormData("attachment", "clients.csv");

            return new ResponseEntity<>(csvData, headers, HttpStatus.OK);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }

    @GetMapping("/produits/excel")
    public ResponseEntity<byte[]> exportProduitsToExcel() {
        try {
            byte[] excelData = exportService.exportProduitsToExcel(produitService.getAllProduits());

            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_OCTET_STREAM);
            headers.setContentDispositionFormData("attachment", "produits.xlsx");

            return new ResponseEntity<>(excelData, headers, HttpStatus.OK);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }

    @GetMapping("/employes/excel")
    public ResponseEntity<byte[]> exportEmployesToExcel() {
        try {
            byte[] excelData = exportService.exportEmployesToExcel(employeService.getAllEmployes());

            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_OCTET_STREAM);
            headers.setContentDispositionFormData("attachment", "employes.xlsx");

            return new ResponseEntity<>(excelData, headers, HttpStatus.OK);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }

    @GetMapping("/fournisseurs/excel")
    public ResponseEntity<byte[]> exportFournisseursToExcel() {
        try {
            byte[] excelData = exportService.exportFournisseursToExcel(fournisseurService.getAllFournisseurs());

            HttpHeaders headers = new HttpHeaders();
            headers.setContentType(MediaType.APPLICATION_OCTET_STREAM);
            headers.setContentDispositionFormData("attachment", "fournisseurs.xlsx");

            return new ResponseEntity<>(excelData, headers, HttpStatus.OK);
        } catch (IOException e) {
            return ResponseEntity.status(HttpStatus.INTERNAL_SERVER_ERROR).build();
        }
    }
}
