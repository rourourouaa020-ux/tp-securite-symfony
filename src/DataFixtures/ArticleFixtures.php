<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Categorie; // ✅ AJOUT ICI

class ArticleFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

   public function load(ObjectManager $manager): void
{
    // 👤 User
    $user = new User();
    $user->setEmail('admin@test.com');
    $user->setPseudo('Admin');
    $user->setRoles(['ROLE_ADMIN']);
    $user->setPassword(
        $this->hasher->hashPassword($user, '123456')
    );

    $manager->persist($user);

    // 📂 Catégories
    $categories = [];
    $catNames = ['Tech', 'Sport', 'Santé'];

    foreach ($catNames as $name) {
        $categorie = new Categorie();
        $categorie->setNom($name);
        $categorie->setDescription("Description de $name");

        $manager->persist($categorie);
        $categories[] = $categorie;
    }

    // 📰 Articles
    for ($i = 1; $i <= 10; $i++) {
        $article = new Article();

        $article->setTitre("Article $i");
        $article->setContenu("Contenu de l'article $i");
        $article->setDateCreation(new \DateTime());
        $article->setPublie(true);

        $article->setAuteurUser($user);
        $article->setAuteur($user->getPseudo());

        // 🔥 association catégorie
        $randomCategorie = $categories[array_rand($categories)];
        $article->setCategorie($randomCategorie);

        $manager->persist($article);
    }

    $manager->flush();
}
}