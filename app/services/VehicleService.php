<?php

class VehicleService extends BaseService
{

    public function upload($id, $file)
    {
        $destinationPath = public_path() . '/img/vehicle/';
        $filename = $id . '_' . str_random(8) . '_' . date("Ymdhis") . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);
    }

    public function save($attributes)
    {
        return $this->persist($attributes,
            function ($params) {
                $vehicle = Vehicle::create($params);
                $this->addSuccessMessage('Adicionado com sucesso!');
                return $vehicle;
            });
    }

    protected function persist($attributes, $callback)
    {
        if (Vehicle::validate($attributes)) {
            return $callback($attributes);
        } else {
            $this->addWarningMessage(Vehicle::getValidationMessages());
        }
    }

    public function update($id, $attributes)
    {
        $this->persist($attributes,
            function ($params) use ($id) {
                $vehicle = Vehicle::find($id);
                $vehicle->color = $params['color'];
                $vehicle->kilometres = $params['kilometres'];
                $vehicle->price = $params['price'];
                $vehicle->vehiclebodystyle_id = $params['vehiclebodystyle_id'];
                $vehicle->vehiclemake_id = $params['vehiclemake_id'];
                $vehicle->vehiclemodelseries_id = $params['vehiclemodelseries_id'];
                $vehicle->save();
                $this->addSuccessMessage('Atualizado com sucesso!');
            });
    }

    public function delete($id)
    {
        $category = Vehicle::find($id);
        if (!is_null($category)) {
            $category->delete();
            $this->addSuccessMessage('Excluído com sucesso!');
        } else {
            $this->addWarningMessage('Não pode ser excluído, porque não existe!');
        }
    }

    public function findOne($id)
    {
        return Vehicle::find($id);
    }

    public function findAll()
    {
        $resultSet = null;
        $query = Vehicle::orderBy('vehiclemake_id')->filter();
        if ($this->hasPagination()) {
            $resultSet = $query->paginate($this->getPageSize());
        } else {
            $resultSet = $query->get();
        }
        $resultSet->load('make', 'modelSeries');
        return $resultSet;
    }
}