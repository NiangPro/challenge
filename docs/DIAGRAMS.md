# Diagrammes de Flux et Architecture

## 1. Architecture Système

```mermaid
graph TD
    A[Client Browser] -->|HTTP/HTTPS| B[Load Balancer]
    B --> C[Web Server]
    C --> D[Application Server]
    D --> E[Database]
    D --> F[Cache Redis]
    D --> G[File Storage]
    
    style A fill:#f9f,stroke:#333,stroke-width:4px
    style E fill:#bbf,stroke:#333,stroke-width:4px
```

## 2. Flux d'Authentification

```mermaid
sequenceDiagram
    participant U as User
    participant C as Client
    participant S as Server
    participant DB as Database
    
    U->>C: Enter Credentials
    C->>S: POST /login
    S->>DB: Verify Credentials
    DB-->>S: User Data
    S-->>C: JWT Token
    C-->>U: Redirect to Dashboard
```

## 3. Processus de Challenge

```mermaid
graph LR
    A[Création Challenge] --> B[Configuration]
    B --> C[Attribution Cohorte]
    C --> D[Tirage]
    D --> E[Matchs]
    E --> F[Évaluation]
    F --> G[Résultats]
    
    style A fill:#f9f
    style G fill:#bbf
```

## 4. Flux de Données

```mermaid
graph TD
    A[Input Data] -->|Validation| B[Controller]
    B -->|Processing| C[Service Layer]
    C -->|CRUD| D[Model]
    D -->|Query| E[Database]
    C -->|Cache| F[Redis]
    B -->|Response| G[View/API]
```

## 5. Processus de Tirage

```mermaid
sequenceDiagram
    participant A as Admin
    participant S as System
    participant DB as Database
    
    A->>S: Initiate Draw
    S->>DB: Get Participants
    DB-->>S: Participants List
    S->>S: Apply Rules
    S->>S: Generate Matches
    S->>DB: Save Matches
    DB-->>S: Confirm
    S-->>A: Show Results
```

## 6. Structure MVC

```mermaid
graph TD
    A[Router] --> B[Controller]
    B --> C[Model]
    B --> D[View]
    C --> E[Database]
    D --> F[Template]
    F --> G[Final Output]
```

## 7. Cycle de Vie d'un Match

```mermaid
stateDiagram-v2
    [*] --> Created
    Created --> Pending
    Pending --> InProgress
    InProgress --> Completed
    InProgress --> Cancelled
    Completed --> [*]
    Cancelled --> [*]
```

## 8. Architecture des Services

```mermaid
graph TD
    A[API Gateway] --> B[Auth Service]
    A --> C[Challenge Service]
    A --> D[Match Service]
    A --> E[User Service]
    
    B --> F[Database]
    C --> F
    D --> F
    E --> F
```

## Notes sur les Diagrammes

1. Les diagrammes sont créés avec Mermaid.js
2. Ils peuvent être visualisés avec un éditeur Markdown compatible
3. Les couleurs indiquent l'importance des composants
4. Les flèches montrent le flux de données/processus
