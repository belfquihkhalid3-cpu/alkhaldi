package com.referentiel.controller;

import com.referentiel.dto.AuthResponse;
import com.referentiel.dto.LoginRequest;
import com.referentiel.dto.RegisterRequest;
import com.referentiel.entity.User;
import com.referentiel.security.JwtService;
import com.referentiel.service.UserService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.web.bind.annotation.*;

import java.util.HashMap;
import java.util.Map;

@RestController
@RequestMapping("/api/auth")
@CrossOrigin(origins = "*")
@RequiredArgsConstructor
public class AuthController {

    private final UserService userService;
    private final JwtService jwtService;
    private final AuthenticationManager authenticationManager;

    @PostMapping("/register")
    public ResponseEntity<?> register(@Valid @RequestBody RegisterRequest request) {
        // Vérifier si le nom d'utilisateur existe déjà
        if (userService.existsByUsername(request.getUsername())) {
            Map<String, String> error = new HashMap<>();
            error.put("message", "Le nom d'utilisateur existe déjà");
            return ResponseEntity.badRequest().body(error);
        }

        // Vérifier si l'email existe déjà
        if (userService.existsByEmail(request.getEmail())) {
            Map<String, String> error = new HashMap<>();
            error.put("message", "L'email existe déjà");
            return ResponseEntity.badRequest().body(error);
        }

        // Créer le nouvel utilisateur
        User user = new User();
        user.setUsername(request.getUsername());
        user.setEmail(request.getEmail());
        user.setPassword(request.getPassword());
        user.setFirstName(request.getFirstName());
        user.setLastName(request.getLastName());

        User createdUser = userService.createUser(user);

        // Générer le token JWT
        UserDetails userDetails = userService.loadUserByUsername(createdUser.getUsername());
        String token = jwtService.generateToken(userDetails);

        AuthResponse response = new AuthResponse(token, createdUser.getUsername(), createdUser.getEmail());
        return ResponseEntity.status(HttpStatus.CREATED).body(response);
    }

    @PostMapping("/login")
    public ResponseEntity<?> login(@Valid @RequestBody LoginRequest request) {
        try {
            // Authentifier l'utilisateur
            Authentication authentication = authenticationManager.authenticate(
                    new UsernamePasswordAuthenticationToken(
                            request.getUsername(),
                            request.getPassword()
                    )
            );

            // Récupérer l'utilisateur
            User user = userService.getUserByUsername(request.getUsername());

            // Générer le token JWT
            UserDetails userDetails = (UserDetails) authentication.getPrincipal();
            String token = jwtService.generateToken(userDetails);

            AuthResponse response = new AuthResponse(token, user.getUsername(), user.getEmail());
            return ResponseEntity.ok(response);
        } catch (Exception e) {
            Map<String, String> error = new HashMap<>();
            error.put("message", "Nom d'utilisateur ou mot de passe incorrect");
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(error);
        }
    }

    @GetMapping("/me")
    public ResponseEntity<User> getCurrentUser(Authentication authentication) {
        String username = authentication.getName();
        User user = userService.getUserByUsername(username);
        return ResponseEntity.ok(user);
    }
}
