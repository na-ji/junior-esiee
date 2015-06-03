<?php

namespace JuniorEsiee\CommentBundle\EventListener;

use FOS\CommentBundle\Events as FOSCommentEvents;
use FOS\CommentBundle\Event\CommentEvent;
use FOS\CommentBundle\Model\CommentManagerInterface;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use JuniorEsiee\NotificationBundle\Services\Notificator;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Listener responsible to send notifications
 * @Service
 * @Tag("kernel.event_subscriber")
 */
class NotificationListener implements EventSubscriberInterface
{
    /**
     * @var CommentManagerInterface
     */
    private $commentManager;

    /**
     * @var Notificator
     */
    private $notificator;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @InjectParams({
     *     "notificator"    = @Inject("je.notification.notificator"),
     *     "commentManager" = @Inject("fos_comment.manager.comment"),
     *     "em"             = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(Notificator $notificator, CommentManagerInterface $commentManager, EntityManager $em, LoggerInterface $logger)
    {
        $this->notificator    = $notificator;
        $this->commentManager = $commentManager;
        $this->em             = $em;
        $this->logger         = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSCommentEvents::COMMENT_PRE_PERSIST => 'onCommentPrePersist',
        );
    }

    public function onCommentPrePersist(CommentEvent $event)
    {
        $comment = $event->getComment();
        $this->logger->debug('Comment received : '.$comment);
        $this->logger->debug('IsNewComment : '.($this->commentManager->isNewComment($comment) ? 'yeah' : 'nope'));

        if (!$this->commentManager->isNewComment($comment)) {
            return;
        }

        $thread        = $comment->getThread();
        $project       = $this->em->getRepository('JuniorEsieeBusinessBundle:Project')->find(substr($thread->getId(), 7));
        $notifiedUsers = new \SplObjectStorage;
        $author        = null;

        if ($comment instanceof SignedCommentInterface) {
            $author = $comment->getAuthor();
        }
        $this->logger->debug('Comment author : '.$author);

        // We notify for replies
        if (null !== $comment->getParent()) {
            $this->logger->debug('We notify for replies');
            $parent = $comment->getParent();
            $this->logger->debug('Comment parent : '.$parent->getAuthor());

            if ($parent instanceof SignedCommentInterface && $parent->getAuthor() !== $author && $parent->getAuthor() !== null) {
                $notifiedUsers->attach($parent->getAuthor());
            }

            $this->logger->debug('Notified users : '.$notifiedUsers->count());

            $replies = $this->commentManager->findCommentTreeByCommentId($parent->getId());

            foreach ($replies as $replyTree) {
                $reply = $replyTree['comment'];
                $this->logger->debug('Reply Tree : '.$reply->getAuthor());

                if ($reply instanceof SignedCommentInterface && $reply->getAuthor() !== $author && $reply->getAuthor() !== null) {
                    $notifiedUsers->attach($reply->getAuthor());
                }
            }
            
            $this->logger->debug('Notified users : '.$notifiedUsers->count());

            $notifiedUsers->rewind();

            while($notifiedUsers->valid()) {
                $user = $notifiedUsers->current();
                $this->logger->debug('Reply to : '.$user);

                $this->notificator->notify(
                    $user, 
                    '{{ getUser('.$author->getId().')|linkUser }} a répondu à votre commentaire sur <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
                    '[Junior] '.$author.' a répondu à votre commentaire sur '.$project,
                    'Bonjour,<br /><br />

                    {{ getUser('.$author->getId().')|linkUser }} a répondu à votre commentaire sur <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.<br /><br />

                    Voici son commentaire : <br />'.$comment->getBody()
                );

                $notifiedUsers->next();
            }
        }

        foreach (array_unique(array($project->getRbu(), $project->getCommercial())) as $user) {
            if (!$notifiedUsers->contains($user) && $user->getId() != $author->getId()) {
                $this->logger->debug('Commercial/Rbu : '.$user);
                $this->notificator->notify(
                    $user, 
                    '{{ getUser('.$author->getId().')|linkUser }} a laissé un commentaire sur <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>',
                    '[Junior] '.$author.' a laissé un commentaire sur '.$project,
                    'Bonjour,<br /><br />

                    {{ getUser('.$author->getId().')|linkUser }} a laissé un commentaire sur <a href="{{ url(\'je_business_project_show\', {id: '.$project->getId().'}) }}">'.$project.'</a>.<br /><br />

                    Voici son commentaire : <br />'.$comment->getBody()
                );
            }
        }
    }
}