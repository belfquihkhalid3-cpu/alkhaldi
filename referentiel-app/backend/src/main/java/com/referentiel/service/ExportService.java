package com.referentiel.service;

import com.opencsv.CSVWriter;
import com.referentiel.entity.Client;
import com.referentiel.entity.Employe;
import com.referentiel.entity.Fournisseur;
import com.referentiel.entity.Produit;
import lombok.RequiredArgsConstructor;
import org.apache.poi.ss.usermodel.*;
import org.apache.poi.xssf.usermodel.XSSFWorkbook;
import org.springframework.stereotype.Service;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.StringWriter;
import java.util.List;

@Service
@RequiredArgsConstructor
public class ExportService {

    // Export Clients vers Excel
    public byte[] exportClientsToExcel(List<Client> clients) throws IOException {
        Workbook workbook = new XSSFWorkbook();
        Sheet sheet = workbook.createSheet("Clients");

        // Créer le style d'en-tête
        CellStyle headerStyle = workbook.createCellStyle();
        Font headerFont = workbook.createFont();
        headerFont.setBold(true);
        headerStyle.setFont(headerFont);
        headerStyle.setFillForegroundColor(IndexedColors.GREY_25_PERCENT.getIndex());
        headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);

        // Créer l'en-tête
        Row headerRow = sheet.createRow(0);
        String[] columns = {"ID", "Nom", "Prénom", "Email", "Téléphone", "Adresse", "Ville", "Code Postal", "Pays", "Entreprise"};
        for (int i = 0; i < columns.length; i++) {
            Cell cell = headerRow.createCell(i);
            cell.setCellValue(columns[i]);
            cell.setCellStyle(headerStyle);
        }

        // Remplir les données
        int rowNum = 1;
        for (Client client : clients) {
            Row row = sheet.createRow(rowNum++);
            row.createCell(0).setCellValue(client.getId());
            row.createCell(1).setCellValue(client.getNom());
            row.createCell(2).setCellValue(client.getPrenom());
            row.createCell(3).setCellValue(client.getEmail());
            row.createCell(4).setCellValue(client.getTelephone());
            row.createCell(5).setCellValue(client.getAdresse());
            row.createCell(6).setCellValue(client.getVille());
            row.createCell(7).setCellValue(client.getCodePostal());
            row.createCell(8).setCellValue(client.getPays());
            row.createCell(9).setCellValue(client.getEntreprise());
        }

        // Auto-size les colonnes
        for (int i = 0; i < columns.length; i++) {
            sheet.autoSizeColumn(i);
        }

        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        workbook.write(outputStream);
        workbook.close();
        return outputStream.toByteArray();
    }

    // Export Clients vers CSV
    public String exportClientsToCSV(List<Client> clients) throws IOException {
        StringWriter writer = new StringWriter();
        CSVWriter csvWriter = new CSVWriter(writer);

        // En-tête
        String[] header = {"ID", "Nom", "Prénom", "Email", "Téléphone", "Adresse", "Ville", "Code Postal", "Pays", "Entreprise"};
        csvWriter.writeNext(header);

        // Données
        for (Client client : clients) {
            String[] data = {
                    String.valueOf(client.getId()),
                    client.getNom(),
                    client.getPrenom(),
                    client.getEmail(),
                    client.getTelephone(),
                    client.getAdresse(),
                    client.getVille(),
                    client.getCodePostal(),
                    client.getPays(),
                    client.getEntreprise()
            };
            csvWriter.writeNext(data);
        }

        csvWriter.close();
        return writer.toString();
    }

    // Export Produits vers Excel
    public byte[] exportProduitsToExcel(List<Produit> produits) throws IOException {
        Workbook workbook = new XSSFWorkbook();
        Sheet sheet = workbook.createSheet("Produits");

        CellStyle headerStyle = createHeaderStyle(workbook);

        Row headerRow = sheet.createRow(0);
        String[] columns = {"ID", "Nom", "Référence", "Description", "Prix", "Quantité Stock", "Catégorie", "Unité", "Actif"};
        for (int i = 0; i < columns.length; i++) {
            Cell cell = headerRow.createCell(i);
            cell.setCellValue(columns[i]);
            cell.setCellStyle(headerStyle);
        }

        int rowNum = 1;
        for (Produit produit : produits) {
            Row row = sheet.createRow(rowNum++);
            row.createCell(0).setCellValue(produit.getId());
            row.createCell(1).setCellValue(produit.getNom());
            row.createCell(2).setCellValue(produit.getReference());
            row.createCell(3).setCellValue(produit.getDescription());
            row.createCell(4).setCellValue(produit.getPrix().doubleValue());
            row.createCell(5).setCellValue(produit.getQuantiteStock() != null ? produit.getQuantiteStock() : 0);
            row.createCell(6).setCellValue(produit.getCategorie());
            row.createCell(7).setCellValue(produit.getUnite());
            row.createCell(8).setCellValue(produit.getActif() ? "Oui" : "Non");
        }

        for (int i = 0; i < columns.length; i++) {
            sheet.autoSizeColumn(i);
        }

        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        workbook.write(outputStream);
        workbook.close();
        return outputStream.toByteArray();
    }

    // Export Employés vers Excel
    public byte[] exportEmployesToExcel(List<Employe> employes) throws IOException {
        Workbook workbook = new XSSFWorkbook();
        Sheet sheet = workbook.createSheet("Employés");

        CellStyle headerStyle = createHeaderStyle(workbook);

        Row headerRow = sheet.createRow(0);
        String[] columns = {"ID", "Nom", "Prénom", "Email", "Téléphone", "Matricule", "Poste", "Département", "Actif"};
        for (int i = 0; i < columns.length; i++) {
            Cell cell = headerRow.createCell(i);
            cell.setCellValue(columns[i]);
            cell.setCellStyle(headerStyle);
        }

        int rowNum = 1;
        for (Employe employe : employes) {
            Row row = sheet.createRow(rowNum++);
            row.createCell(0).setCellValue(employe.getId());
            row.createCell(1).setCellValue(employe.getNom());
            row.createCell(2).setCellValue(employe.getPrenom());
            row.createCell(3).setCellValue(employe.getEmail());
            row.createCell(4).setCellValue(employe.getTelephone());
            row.createCell(5).setCellValue(employe.getMatricule());
            row.createCell(6).setCellValue(employe.getPoste());
            row.createCell(7).setCellValue(employe.getDepartement());
            row.createCell(8).setCellValue(employe.getActif() ? "Oui" : "Non");
        }

        for (int i = 0; i < columns.length; i++) {
            sheet.autoSizeColumn(i);
        }

        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        workbook.write(outputStream);
        workbook.close();
        return outputStream.toByteArray();
    }

    // Export Fournisseurs vers Excel
    public byte[] exportFournisseursToExcel(List<Fournisseur> fournisseurs) throws IOException {
        Workbook workbook = new XSSFWorkbook();
        Sheet sheet = workbook.createSheet("Fournisseurs");

        CellStyle headerStyle = createHeaderStyle(workbook);

        Row headerRow = sheet.createRow(0);
        String[] columns = {"ID", "Nom Entreprise", "Contact", "Email", "Téléphone", "Adresse", "Ville", "Pays", "N° SIRET", "Actif"};
        for (int i = 0; i < columns.length; i++) {
            Cell cell = headerRow.createCell(i);
            cell.setCellValue(columns[i]);
            cell.setCellStyle(headerStyle);
        }

        int rowNum = 1;
        for (Fournisseur fournisseur : fournisseurs) {
            Row row = sheet.createRow(rowNum++);
            row.createCell(0).setCellValue(fournisseur.getId());
            row.createCell(1).setCellValue(fournisseur.getNomEntreprise());
            row.createCell(2).setCellValue(fournisseur.getContact());
            row.createCell(3).setCellValue(fournisseur.getEmail());
            row.createCell(4).setCellValue(fournisseur.getTelephone());
            row.createCell(5).setCellValue(fournisseur.getAdresse());
            row.createCell(6).setCellValue(fournisseur.getVille());
            row.createCell(7).setCellValue(fournisseur.getPays());
            row.createCell(8).setCellValue(fournisseur.getNumeroSiret());
            row.createCell(9).setCellValue(fournisseur.getActif() ? "Oui" : "Non");
        }

        for (int i = 0; i < columns.length; i++) {
            sheet.autoSizeColumn(i);
        }

        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        workbook.write(outputStream);
        workbook.close();
        return outputStream.toByteArray();
    }

    private CellStyle createHeaderStyle(Workbook workbook) {
        CellStyle headerStyle = workbook.createCellStyle();
        Font headerFont = workbook.createFont();
        headerFont.setBold(true);
        headerStyle.setFont(headerFont);
        headerStyle.setFillForegroundColor(IndexedColors.GREY_25_PERCENT.getIndex());
        headerStyle.setFillPattern(FillPatternType.SOLID_FOREGROUND);
        return headerStyle;
    }
}
