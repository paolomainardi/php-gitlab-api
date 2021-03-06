<?php namespace Gitlab\Model;

use Gitlab\Client;

/**
 * Class ProjectHook
 *
 * @property-read int $id
 * @property-read string $url
 * @property-read int $project_id
 * @property-read bool $push_events
 * @property-read bool $issues_events
 * @property-read bool $merge_requests_events
 * @property-read bool $tag_push_events
 * @property-read string $created_at
 * @property-read Project $project
 */
class ProjectHook extends AbstractModel
{
    /**
     * @var array
     */
    protected static $properties = array(
        'id',
        'project',
        'url',
        'project_id',
        'push_events',
        'issues_events',
        'merge_requests_events',
        'tag_push_events',
        'created_at'
    );

    /**
     * @param Client  $client
     * @param Project $project
     * @param array   $data
     * @return ProjectHook
     */
    public static function fromArray(Client $client, Project $project, array $data)
    {
        $hook = new static($project, $data['id'], $client);

        return $hook->hydrate($data);
    }

    /**
     * @param Project $project
     * @param int $id
     * @param Client $client
     */
    public function __construct(Project $project, $id, Client $client = null)
    {
        $this->setClient($client);
        $this->setData('project', $project);
        $this->setData('id', $id);
    }

    /**
     * @return ProjectHook
     */
    public function show()
    {
        $data = $this->api('projects')->hook($this->project->id, $this->id);

        return static::fromArray($this->getClient(), $this->project, $data);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $this->api('projects')->removeHook($this->project->id, $this->id);

        return true;
    }

    /**
     * @return bool
     */
    public function remove()
    {
        return $this->delete();
    }

    /**
     * @param array $params
     * @return ProjectHook
     */
    public function update(array $params)
    {
        $data = $this->api('projects')->updateHook($this->project->id, $this->id, $params);

        return static::fromArray($this->getClient(), $this->project, $data);
    }
}
