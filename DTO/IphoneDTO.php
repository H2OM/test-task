<?php

namespace DTO;

class IphoneDTO
{
    private string $title;
    private string $description;
    private string $category;
    private string $rating;
    private string $images;

    //...
    //Из API передается много полей, все использовать не буду

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @return string
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * @return array
     */
    public function getAllFields(): array
    {
        return array_keys(get_class_vars(__CLASS__));
    }

    /**
     * @return array
     */
    public function getAllVars(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param array|string $images
     * @return void
     */
    public function setImages(array|string $images): void
    {
        if(is_array($images)){
            $this->images = implode(',', $images);
        } else {
            $this->images = $images;

        }
    }

    /**
     * @param array $object
     * @return bool
     */
    public function setSelf(array $object): bool
    {
        if (isset($object['title'], $object['description'], $object['category'], $object['rating']) && str_contains($object['title'], 'iPhone')) {
            $this->title = $object['title'];
            $this->description = $object['description'];
            $this->category = $object['category'];
            $this->rating = $object['rating'];
            $this->setImages($object['images']);

            return true;
        } else {
            return false;
        }
    }


}
