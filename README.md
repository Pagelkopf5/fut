# fut-manager

## Business Logic:

We have football teams. Each team has a name, country, money balance and players.
Each player has name and surname.
Teams can sell/buy players.

What we need in our app:
- Make a page (with pagination) displaying all teams and their players.
- Make a page where we can add a new team and its players.
- Make a page where we can sell/buy a player for a certain amount between two teams.

### Requirements:

You should use the Symfony PHP Framework (please don't use API Platform).
Follow PSR-12/PER, in JS follow JavaScript Standard Style.
Unit tests are welcome.
Add a README file with installation and startup instructions.
Do not use CRUD bundles like EasyAdmin.
Treat the task as a full-fledged project.

### Database

team
  logo
  name
  country
  money

player
  name
  surname
  country
  age
  value
  team_id

offer
  team_id
  player_id
  value
  status

### Idea

pagina de times - abre o time e mostra os jogadores - 
pagina de todos jogadores - tabelão
  nesta pagina pode se fazer oferta pelo jogador
pagina de ofertas

## Commands

symfony server:start
symfony server:stop