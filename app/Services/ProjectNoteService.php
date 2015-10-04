<?php

namespace ProjectManager\Services;

use ProjectManager\Repositories\ProjectNoteRepository;
use ProjectManager\Validators\ProjectNoteValidator;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectNoteService 
{
    /**
     * @var ProjectNoteRepository
     */
    protected $repository;
    
    /**
     * @var ProjectNoteValidator
     */
    protected $validator;
    
    public function __construct(
        ProjectNoteRepository $repository, 
        ProjectNoteValidator $validator
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
                'message' => $e->getMessageBag()
            ];
        }
    }
    
    public function show($id, $noteId)
    {
        try {
            $note = (array) $this
                ->repository
                ->findWhere(['project_id' => $id, 'id' => $noteId]);
            
            return array_shift($note['data']);
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
            $this->repository->skipPresenter()->find($id)->delete();
            return json_encode('{id: ' + $id + '}');
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
