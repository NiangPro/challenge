-- Ajouter un champ pour le numéro du tour
ALTER TABLE matches ADD COLUMN tour INT DEFAULT 1;
-- Ajouter un champ pour indiquer si le match est archivé
ALTER TABLE matches ADD COLUMN is_archived BOOLEAN DEFAULT FALSE;
