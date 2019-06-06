<?php
/**
 * Created by PhpStorm.
 * User: SbWereWolf
 * Date: 2019-06-03
 * Time: 17:53
 */

/*

Вопрос 1
Представьте, что вы проектируете блог. Необходимо реализовать три сущности - пользователь, менеджер и администратор.
В сущностях нужно реализовать только методы canEdit и canDelete а также любые вспомогательные методы.

*/

namespace OmegaSport;

interface IMessage
{

    /**
     * Возвращает является ли сообщение принадлежащим пользователю с переданным идентификатором
     * @param int $userId
     * @return bool
     */
    public function isBelonging(int $userId): bool;

    /**
     * Возвращает роль автора сообщения
     * @return IRole
     */
    public function getAuthorRole(): IRole;
}

interface Authorizable
{
    public function canEdit(IMessage $message): bool;

    public function canDelete(IMessage $message): bool;
}

interface IRole
{
    public function isCustomer(): bool;

    public function isManager(): bool;

    public function isAdmin(): bool;
}

interface IUserRepository
{
    public function getUserRole(int $id): IRole;
}

interface IUser
{
    public function getId(): int;

    public function getRole(): IRole;

    public function isOwnMessage(IMessage $message): bool;
}

class Message implements IMessage
{

    /* @var $author IUser */
    private $author;
    private $content;

    /**
     * Message constructor.
     * @param IUser $user
     * @param $content
     */
    public function __construct(IUser $user, $content)
    {
        $this->setAuthor($user)->setContent($content);
    }

    /**
     * @param mixed $content
     * @return self
     */
    private function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param IUser $user
     * @return self
     */
    private function setAuthor(IUser $user): self
    {
        $this->author = $user;
        return $this;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isBelonging(int $userId): bool
    {
        $isAuthor = ($userId == $this->author->getId());

        return $isAuthor;
    }

    /**
     * @return IRole
     */
    public function getAuthorRole(): IRole
    {
        return $this->author->getRole();
    }
}

class Role implements IRole
{

    public function isCustomer(): bool
    {
        // TODO: Implement isCustomer() method.
    }

    public function isManager(): bool
    {
        // TODO: Implement isManager() method.
    }

    public function isAdmin(): bool
    {
        // TODO: Implement isAdministrator() method.
    }
}

class UserRepository implements IUserRepository
{
    public function getUserRole(int $id): IRole
    {
        // TODO: Implement getUserRole($id) method.
    }
}

/*
 Класс User из Технического Задания переименован в Customer,
 поскольку пользователь Блога (программного продукта) это так же и менеджер, и администратор

 Реализованный класс User это любой пользователь Блога
 Допустим что любой пользователь Блога может создавать сообщения,
 в таком контектсе реализация метода isOwnMessage в классе User будет уместной

*/

class User implements IUser
{

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /* Мне кажется в рамках тестового задания более аутентичным было бы свойство $roleName */

    public function getRole(): IRole
    {
        return (new UserRepository())->getUserRole($this->getId());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isOwnMessage(IMessage $message): bool
    {
        return $message->isBelonging($this->getId());
    }
}

/**
 * Пользователь
 * Может редактировать только свои сообщения
 * Удалять сообщения не может
 * Class User
 */
class Customer extends User implements Authorizable
{
    /**
     * Проверяет, может ли пользователь редактировать сообщение
     * @param IMessage $message
     * @return bool
     */

    /*
    Сигнатура вызова была изменена
    через параметр $message теперь следует передавать переменную с реализацией интерфейса IMessage
    */
    public function canEdit(IMessage $message): bool
    {
        /* Может редактировать только свои сообщения */
        return $this->isOwnMessage($message);
    }


    /**
     * Проверяет, может ли пользователь удалить сообщение
     * @param IMessage $message
     * @return bool
     */

    /*
    Сигнатура вызова была изменена
    через параметр $message теперь следует передавать переменную с реализацией интерфейса IMessage
    */
    public function canDelete(IMessage $message): bool
    {
        /* Удалять сообщения не может */
        return false;
    }
}

/**
 * Менеджер
 * Может редактировать свои сообщения и собщения других пользователей
 * Удалять сообщения не может
 * Class Manager
 */
class Manager extends User implements Authorizable
{

    public function canEdit(IMessage $message): bool
    {
        /* Может редактировать свои сообщения*/
        $isOwn = $this->isOwnMessage($message);

        /* Может редактировать .. собщения других пользователей */
        /* "Другие пользователи" это пользователи Блога c любой ролью ? или только с ролью "Пользователь" ?  */
        /* Если под "другим пользователем" понимается роль "Пользователь", то проверяем */
        $isCustomerMessage = $message->getAuthorRole()->isCustomer();
        /* $message->getAuthorRole()->isCustomer(); можно было бы инкапсулировать в $message->isCustomer(),
        но мне кажется это овер-инжиниринг для такого крошечного тестового задания */

        /* Если под "пользователем" подразумевается любой пользователь Блога (и админ, и менеджер) то не проверяем
        $isCustomerMessage = true;
        */

        return $isOwn || $isCustomerMessage;
    }

    public function canDelete(IMessage $message): bool
    {
        /* Удалять сообщения не может */
        return false;
    }
}

/**
 * Администратор
 * Может редактировать и удалять любые сообщения
 * Class Admin
 */
class Admin extends User implements Authorizable
{
    public function canEdit(IMessage $message): bool
    {
        /* Может редактировать любые сообщения */
        return true;
    }

    public function canDelete(IMessage $message): bool
    {
        /* Может удалять любые сообщения */
        return true;
    }
}

/*

По Тех Заданию требовалось реализовать методы canEdit и canDelete,
для наглядности пришлось реализовать несколько классов и интерфейсов,
процесс проектирования увлекательный и наверное надо было остановиться на класссе User,
и вместо
    public function getRole(): IRole
    {
        return (new UserRepository())->getUserRole($this->getId());
    }
написать
    public function isCustomer(): IRole
    {
        return (new UserRepository())->isCustomer($this->getId());
    }
но бритву Оккама я применяю во время код ревью перед комитом,
и сейчас тратить ещё пол часа на доводку уже лень и так два часа проектировал,
для начала общения написанного достаточно ?

*/
