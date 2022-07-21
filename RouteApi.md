## Routes Api USER antares

| Endpoint                      | Méthode HTTP | Description                                                                                   | Retour                          |
| ----------------------------- | ------------ | --------------------------------------------------------------------------------------------- | ------------------------------- |
| `/api/v1/users`              | `GET`        | Récupération de tous les users                                                               | 200                             |
| `/api/v1/users/{id}`         | `GET`        | Récupération du utilisateur dont l'id est fourni                                                     | 200 ou 404                      |
| `/api/v1/registration`              | `POST`       | Ajout d'un utilisateur _+ la donnée JSON qui représente le nouveau utilisateur                             | 201 + Location: /users/{newID} |
| `/api/v1/users/{id}`         | `PUT`        | Modification d'un utilisateur dont l'id est fourni _+ la donnée JSON qui représente le utilisateur modifié_ | 200, 204 ou 404                 |
| `/api/v1/users/{id}`         | `DELETE`     | Suppression d'un utilisateur dont l'id est fourni                                                    | 200 ou 404                      |
