<?php
final class dbHandler
{
    private $dataSource = "localhost"; //Hier dient je connection string te komen mysql:dbname=;host=;
    private $username = "root";
    private $password = "";

    private $conn;

    public function connection(){
        try{
            $this->conn = new PDO("mysql:host=$this->dataSource;dbname=wordle", $this->username, $this->password);
            echo "Connected successfully";

            return $this->conn;
        }
        catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function selectAll()
    {
        try{
            $this->conn = $this->connection();

            $words = $this->conn->prepare("SELECT word.text, category.name, word.wordId FROM word INNER JOIN category ON word.categoryId = category.categoryId");
            $words->execute();

            $result = $words->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            //Maak een nieuwe PDO
            //Maak gebruik van de prepare functie van PDO om alle woorden op te halen met bijbehorende categorie (Join)
            //Voer het statement uit
            //Return een associatieve array met alle opgehaalde data.
        }
        catch(PDOException $e){

            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }

    public function selectCategories()
    {
        try{
            $this->conn = $this->connection();

            $categories = $this->conn->prepare("SELECT * FROM category");
            $categories->execute();

            $result = $categories->fetchAll(PDO::FETCH_ASSOC);

            return $result;
            //Hier doe je grootendeels hetzelfde als bij SelectAll, echter selecteer je alleen alles uit de category tabel.
        }
        catch(PDOException $e){
            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }

    public function createWord($text, $categoryId)
    {
        try{
            $this->conn = $this->connection();

            if(isset($_POST['create'])) {
            $word = $_POST['text'];
            $category = $_POST['category'];
            
            $create = $this->conn->prepare("INSERT INTO `word` (`wordId`, `categoryId`, `text`) VALUES (NULL, '$category', '$word')");
            $create->execute();

            header("location: index.php");
            exit;
            }

            
            //Maak een nieuwe PDO
            //Maak gebruik van de prepare functie van PDO om een insert into uit te voeren op de tabel word.
            //De kolommen die gevuld moeten worden zijn text en categoryId
            //Gebruik binding om de parameters aan de juiste kolommen te koppellen
            //Voer het statement uit
            //Return een associatieve array met alle opgehaalde data.
            //Voer de query uit en return true omdat alles goed is gegaan
        }
        catch(PDOException $e){
            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }

    public function selectOne($wordId){
        try{
            $this->conn = $this->connection();

            $selectOne = $this->conn->prepare("SELECT * FROM word INNER JOIN category ON word.categoryId = category.categoryId WHERE wordId = :wordId");
            $selectOne->bindParam(':wordId', $wordId);
            $selectOne->execute();
            
            $rows = $selectOne->fetch(PDO::FETCH_ASSOC);
            return $rows;
            //Maak een nieuwe PDO
            //Maak gebruik van de prepare functie van PDO om een select uit te voeren van 1 woord. Degene met het opgegeven Id
            //Let op dat de categorie wederom gejoined moet worden, en de wordId middels een parameter moet worden gekoppeld.
            //Voer het statement uit
            //maak een variabele $rows met een associatieve array met alle opgehaalde data.
            //we willen enkel 1 resultaat ophalen dus zorg dat de eerste regel van de array wordt gereturned.
        }
        catch(PDOException $e){
            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }

    public function updateWord($wordId, $text, $category){
        try{
            $this->conn = $this->connection();

            $update = $this->conn->prepare("UPDATE `word` SET `categoryId` = :category, `text` = :text WHERE `word`.`wordId` = :wordId;");
            $update->bindParam(':text', $text);
            $update->bindParam(':category', $category);
            $update->bindParam(':wordId', $wordId);
            $update->execute();
            //Maak een nieuwe PDO
            //Maak gebruik van de prepare functie van PDO om een update uit te voeren van 1 woord. Degene met het opgegeven Id
            //Let op dat zowel de velden die je wilt wijzigen (categorie en text) met parameters gekoppeld moeten worden
            //De wordId gebruik je voor een WHERE statement.
            //bind alle 3 je parameters
            //voer de query uit en return true.
        }
        catch(PDOException $e){
            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }
    }

    public function deleteWord($id){
        try{
            $this->conn = $this->connection();

            $delete = $this->conn->prepare("DELETE FROM word WHERE wordId = :id");
            $delete->bindParam(':id', $id);
            $delete->execute();

            $result = $delete->fetchAll(PDO::FETCH_ASSOC);
            return $result;
            //Maak een nieuwe PDO
            //Maak gebruik van de prepare functie van PDO om een delete uit te voeren van 1 woord. Degene met het opgegeven Id
            //De wordId gebruik je voor een WHERE statement.
            //bind je parameter
            //voer de query uit en return true.
        }
        catch(PDOException $e){
            return false;
            //Indien er iets fout gaat kun je hier de exception var_dumpen om te achterhalen wat het probleem is.
            //Return false zodat het script waar deze functie uitgevoerd wordt ook weet dat het misgegaan is.
        }

    }
}