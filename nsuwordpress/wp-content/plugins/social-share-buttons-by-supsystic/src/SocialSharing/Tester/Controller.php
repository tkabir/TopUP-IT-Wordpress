<?php


class SocialSharing_Tester_Controller extends SocialSharing_Core_BaseController
{
    public function indexAction(Rsc_Http_Request $request)
    {
        $projects = $this->modelsFactory->get('projects')->all();

        return $this->response('@tester/index.twig', array('projects' => $projects, 'notice' => (bool)$request->query->get('notice', false)));
    }

    public function updateAction(Rsc_Http_Request $request)
    {
        $id = $request->post->get('project');
        $count = $request->post->get('count');
        $networks = $this->modelsFactory->get('networks')->all();

        $posts = get_posts(array('posts_per_page' => -1));

        for ($i = 0; $i <= $count; $i++) {
            $network = mt_rand(0, count($networks) - 1);
            $post = $posts[array_rand($posts)];

            $this->modelsFactory->get('shares')->add($id, $networks[$network]->id, $post->ID);
        }

        return $this->redirect($this->generateUrl('tester', 'index', array('notice' => true)));
    }
}