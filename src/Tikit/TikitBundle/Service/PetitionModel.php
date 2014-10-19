<?php
// Service Code
namespace Tikit\TikitBundle\Service;

use Doctrine\ORM\Mapping as ORM;
use Tikit\TikitBundle\Entity\Petition;
use Tikit\TikitBundle\Entity\PetitionScore;
use Tikit\TikitBundle\Entity\SiteSpam;
use Tikit\TikitBundle\Entity\SpamUserCount;

use Doctrine\ORM\Query\ResultSetMapping;

class PetitionModel
{
//
    const LINK_ATTACH_TYPE            = 'link';
    const VIDEO_ATTACH_TYPE           = 'video';


    protected $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function addPetition($entity, $user_id)
    {       
        //$em = $this->getDoctrine()->getManager();
        $this->setPetitionUrlModel($entity);
        $user = $this->em->getRepository('\Acme\UserBundle\Entity\User')->findOneBy(array('id' => $user_id));
        $entity->setUser($user);
        $this->em->persist($entity);
        $petition_score = new PetitionScore($entity,$user,1);
        $petition_score->setPetition($entity);
        $petition_score->setUser($user);
        $this->em->persist($petition_score);
        $this->em->flush();
    }
    
    public function setPetitionUrlModel($entity)
    {
        $petitionTitle = $entity->getPetitionTitle();
        $petitionUrl = $this->url_slug($petitionTitle);
        $petitionUrlOldEnt = $this->em->getRepository('\Tikit\TikitBundle\Entity\Petition')->findOneBy(array('petitionUrl' => $petitionUrl));
        if ($petitionUrlOldEnt) {
            $petitionUrlOld = $petitionUrlOldEnt->getPetitionUrl();
            if ($petitionUrlOld == $petitionUrl) {
                $petitionUrl = $petitionUrl . '-' . rand(999, 9999);
            }
        }
        $entity->setPetitionUrl($petitionUrl);
    }
    
   
    public function url_slug($str, $options = array())
    {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	$options = array(
		'delimiter' => '-',
		'limit' => 100,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => true
	);
	
	// Merge options
	//$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
 
		// Latin symbols
		'©' => '(c)',
 
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
 
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
 
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
 
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
 
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;  
    }
        
    public function getPetitionFromReferer($referer)
    {
        $referer = str_replace('http://petit/app_dev.php/p/', "", $referer);;
        $petition = $this->em->getRepository('\Tikit\TikitBundle\Entity\Petition')->findOneBy(array('petitionUrl' => $referer));
        return $petition->getId();
    }

    public function votePetition($petition_id,$user_id)
    {
        $vote = 1;
        $petitionscore = $this->em->getRepository('\Tikit\TikitBundle\Entity\PetitionScore')->findOneBy(array('user' => $user_id, 'petition' => $petition_id));
        if(!$petitionscore){
            $petition = $this->em->find('\Tikit\TikitBundle\Entity\Petition', $petition_id);
            $petition->setScore($petition->getScore() + $vote);
            $petitionscore = new PetitionScore($petition_id,$user_id,$vote);
            $petitionscore->setPetition($petition);
            $user = $this->em->find('\Acme\UserBundle\Entity\User', $user_id);
            $petitionscore->setUser($user);
            $this->em->persist($petitionscore);
            $this->em->persist($petition);
            $this->em->flush();
            return 1;
        }
        return 0;
    }

    public function preUserApprovedVote($petitionId, $user, $vote)
    {
        $user_id = $user->getId();
        $petitionscore = $this->em->getRepository('\Tikit\TikitBundle\Entity\PetitionScore')->findOneBy(array('user' => $user_id, 'petition' => $petitionId));
        if(!$petitionscore){
            //$petition = $this->em->find('\Tikit\TikitBundle\Entity\Petition', $petitionId);
            //$petition->setScore($petition->getScore() + $vote);
            $petitionscore = new PetitionScore($petitionId, $user_id, $vote, true);
            
            $petition = $this->em->find('\Tikit\TikitBundle\Entity\Petition', $petitionId);
            $petitionscore->setPetition($petition);
            $user = $this->em->find('\Acme\UserBundle\Entity\User', $user_id);
            $petitionscore->setUser($user);
            //$petitionscore->setPetition($petition);
            //$petitionscore->setUser($user);
            $this->em->persist($petitionscore);
            //$this->em->persist($petition);
            $this->em->flush();
            return 1;
        }
        return 0;
    }

    public function postUserApprovedVote($user)
    {
        $user_id = $user->getId();
        $petitionscore = $this->em->getRepository('\Tikit\TikitBundle\Entity\PetitionScore')->findOneBy(array('user' => $user_id));
        if($petitionscore){
            $petition = $petitionscore->getPetition();
            //$petition = $this->em->find('\Tikit\TikitBundle\Entity\Petition', $petitionId);
            if ($petition) {
                $petition->setScore($petition->getScore() + 1);
                $dateAdded = new \DateTime('now');
                $petitionscore->setDateAdded($dateAdded);
                //$petitionscore = new PetitionScore($petitionId, $user_id, $vote, true);
                //$petitionscore->setPetition($petition);
                //$user = $this->em->find('\Tikit\TikitBundle\Entity\FosUser', $user_id);
                //$petitionscore->setUser($user);
                $this->em->persist($petitionscore);
                $this->em->persist($petition);
                $this->em->flush();
            }
            return 1;
        }
        return 0;
    }

    public function getPetitions($count_per_page,$offset)
    {
        $query = $this->em->createQuery('SELECT t, u.username, s.vote, c.categoryName, c.id as cid 
                                    FROM \Tikit\TikitBundle\Entity\Petition t
                                    JOIN \Tikit\TikitBundle\Entity\Category c WITH t.category = c.id
                                    LEFT JOIN \Acme\UserBundle\Entity\User u WITH u.id = t.user
                                    LEFT JOIN \Tikit\TikitBundle\Entity\PetitionScore s
                                    WITH s.user = t.user AND s.petition = t.id
                                    AND t.status = 1 ORDER BY t.dateAdded DESC')
                    ->setMaxResults($count_per_page)
                    ->setFirstResult($offset);
        $tikits = $query->getResult();
        return $tikits;
    }
 
    public function getMostPopularPetitions($count_per_page,$offset)
    {
        $query = $this->em->createQuery('SELECT t, u.username, s.vote, c.categoryName, c.id as cid 
                                    FROM \Tikit\TikitBundle\Entity\Petition t
                                    JOIN \Tikit\TikitBundle\Entity\Category c WITH t.category = c.id
                                    LEFT JOIN \Acme\UserBundle\Entity\User u WITH u.id = t.user
                                    LEFT JOIN \Tikit\TikitBundle\Entity\PetitionScore s
                                    WITH s.user = t.user AND s.petition = t.id
                                    AND t.status = 1 ORDER BY t.score DESC')
                    ->setMaxResults($count_per_page)
                    ->setFirstResult($offset);
        $tikits = $query->getResult();
        return $tikits;
    }
 
    public function getPetitionsByCategory($count_per_page, $offset, $category)
    {
        $query = $this->em->createQuery('SELECT t, u.username, s.vote, c.categoryName FROM \Tikit\TikitBundle\Entity\Petition t
                                    JOIN \Tikit\TikitBundle\Entity\Category c WITH t.category = c.id
                                    LEFT JOIN \Acme\UserBundle\Entity\User u WITH u.id = t.user
                                    LEFT JOIN \Tikit\TikitBundle\Entity\PetitionScore s
                                    WITH s.user = t.user AND s.petition = t.id
                                    AND t.status = 1 WHERE t.category = :category ORDER BY t.score DESC')
                    ->setParameter('category', $category)
                    ->setMaxResults($count_per_page)
                    ->setFirstResult($offset);
        $tikits = $query->getResult();
        return $tikits;
    }
    
    public function getTotalPetitions()
    {
        $count = $this->em->createQuery('SELECT COUNT(p.id) FROM Tikit\TikitBundle\Entity\Petition p
                JOIN \Tikit\TikitBundle\Entity\Category c WITH p.category = c.id WHERE p.status = 1 AND c.id = 1')
                    ->getSingleScalarResult();
        return $count;
    }

    public function getTotalPetitionsByCategory($category)
    {
        $count = $this->em->createQuery('SELECT COUNT(p.id) FROM Tikit\TikitBundle\Entity\Petition p WHERE p.status = 1
                AND p.category = :category ')
                    ->setParameter('category', $category)
                    ->getSingleScalarResult();
        return $count;
    }

    public function getCategories()
    {
        $query = $this->em->createQuery('SELECT c.id, c.categoryName FROM \Tikit\TikitBundle\Entity\Category c
               ORDER BY c.id DESC');
        $categories = $query->getResult();
        return $categories;
    }

}