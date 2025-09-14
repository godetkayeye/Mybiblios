<?php

namespace App\Entity;

use App\Repository\EditorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EditorRepository::class)]
class Editor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, book>
     */
    #[ORM\OneToMany(targetEntity: book::class, mappedBy: 'editor')]
    private Collection $books;

    /**
     * @var Collection<int, Author>
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'editors')]
    private Collection $book;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'editor')]
    private ?self $Book = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'Book')]
    private Collection $editor;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->book = new ArrayCollection();
        $this->editor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setEditor($this);
        }

        return $this;
    }

    public function removeBook(book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getEditor() === $this) {
                $book->setEditor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function setBook(?self $Book): static
    {
        $this->Book = $Book;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEditor(): Collection
    {
        return $this->editor;
    }

    public function addEditor(self $editor): static
    {
        if (!$this->editor->contains($editor)) {
            $this->editor->add($editor);
            $editor->setBook($this);
        }

        return $this;
    }

    public function removeEditor(self $editor): static
    {
        if ($this->editor->removeElement($editor)) {
            // set the owning side to null (unless already changed)
            if ($editor->getBook() === $this) {
                $editor->setBook(null);
            }
        }

        return $this;
    }
}
