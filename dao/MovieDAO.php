<?php

require_once("models/Movie.php");
require_once("models/Message.php");

// REVIEW DAO

class MovieDAO implements MovieDAOInterface
{

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }

    public function buildMovie($data)
    {
        $movie = new Movie();

        $movie->id = $data["id"];
        $movie->title = $data["title"];
        $movie->description = $data["description"];
        $movie->image = $data["image"];
        $movie->trailer = $data["trailer"];
        $movie->category = $data["category"];
        $movie->lenght = $data["lenght"];
        $movie->users_id = $data["users_id"];

        return $movie;
    }
    public function findAll()
    {
    }
    public function getLatestMovies()
    {
        $movies = [];

        $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $moviesArray = $stmt->fetchAll();

            foreach ($moviesArray as $movie) {

                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }
    public function getMoviesByCategory($category)
    {
        $stmt = $this->conn->prepare("SELECT * FROM movies 
        WHERE category = :category
        ORDER BY id DESC");

        $stmt->bindParam(":category", $category);

        $stmt->execute();

        $moviesArray = $stmt->fetchAll();

        $movies = [];

        if (count($moviesArray) > 0) {


            foreach ($moviesArray as $movie) {

                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }
    public function getMoviesByUserId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM movies 
        WHERE users_id = :users_id");

        $stmt->bindParam(":users_id", $id);

        $stmt->execute();

        $moviesArray = $stmt->fetchAll();

        $movies = [];

        if (count($moviesArray) > 0) {


            foreach ($moviesArray as $movie) {

                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }
    public function findById($id)
    {
        $movie = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies 
        WHERE id = :id");

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $moviedata = $stmt->fetch();
            $movie = $this->buildMovie($moviedata);
            return $movie;
        } else {
            return false;
        }
    }
    public function findByTitle($title)
    {
        $movies = [];

        $stmt = $this->conn->prepare("SELECT * FROM movies 
        WHERE title LIKE :title");

        $stmt->bindValue(":title", '%' . $title . '%');

        $stmt->execute();

        $moviedata = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($moviedata as $data) {

           
            $movies[] = $this->buildMovie($data);
        }


        return $movies;
    }
    public function create(Movie $movie)
    {
        $stmt = $this->conn->prepare("INSERT INTO movies(title, description, image, trailer,
        category, lenght, users_id)
        VALUES(:title, :description, :image, :trailer, :category, :lenght, :users_id)");
        $stmt->bindParam(":title", $movie->title);
        $stmt->bindParam(":description", $movie->description);
        $stmt->bindParam(":image", $movie->image);
        $stmt->bindParam(":trailer", $movie->trailer);
        $stmt->bindParam(":category", $movie->category);
        $stmt->bindParam(":lenght", $movie->lenght);
        $stmt->bindParam(":users_id", $movie->users_id);

        $stmt->execute();

        // Mesnsagem de sucesso!
        $this->message->setMessage("Filme adicionado com sucesso!!!", "success", "index.php");
    }
    public function update(Movie $movie)
    {
        $stmt = $this->conn->prepare("UPDATE movies SET 
        title = :title,
        description = :description,
        image = :image,
        trailer = :trailer,
        category = :category,
        lenght = :lenght
        WHERE id = :id");

        $stmt->bindParam("title", $movie->title);
        $stmt->bindParam("description", $movie->description);
        $stmt->bindParam("image", $movie->image);
        $stmt->bindParam("trailer", $movie->trailer);
        $stmt->bindParam("category", $movie->category);
        $stmt->bindParam("lenght", $movie->lenght);
        $stmt->bindParam("id", $movie->id);

        $stmt->execute();




        // REDIRECIONA PARA O PERFIL DO USUÁRIO
        $this->message->setMessage("Filme atualizado com sucesso!!!", "success", "dashboard.php");
    }
    public function destroy($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM movies WHERE id = :id");

        $stmt->bindParam(":id", $id);

        $stmt->execute();

        // Mesnsagem de sucesso!
        $this->message->setMessage("Filme removido com sucesso!!!", "success", "dashboard.php");
    }
}
