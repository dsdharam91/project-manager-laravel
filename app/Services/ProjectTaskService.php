<?php

namespace ProjectManager\Services;

use ProjectManager\Repositories\ProjectTaskRepository;
use ProjectManager\Validators\ProjectTaskValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectTaskService 
{
    /**
     * @var ProjectTaskRepository
     */
    protected $repository;
    
    /**
     * @var ProjectTaskValidator
     */
    protected $validator;
    
    public function __construct(
        ProjectTaskRepository $repository, 
        ProjectTaskValidator $validator
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
    }
    
    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);
        } catch (ValidatorException $e) {
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }
    
    public function update(array $data, $id)
    {
        try {
            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function show($id, $taskId)
    {
        try {
            return $this->repository->findWhere(['project_id' => $id, 'id' => $taskId]);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function destroy($id)
    {
        try {
            $this->repository->find($id)->delete();
            return [$id];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}